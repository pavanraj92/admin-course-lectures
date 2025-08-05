<?php

namespace admin\courses\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\courses\Requests\LectureCreateRequest;
use admin\courses\Requests\LectureUpdateRequest;
use admin\courses\Models\Lecture;
use admin\courses\Models\Course;
use admin\courses\Models\CourseSection as Section;
use Illuminate\Support\Str;
use admin\admin_auth\Services\ImageService;

class LectureManagerController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('admincan_permission:lectures_manager_list')->only(['index','list']);
        $this->middleware('admincan_permission:lectures_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:lectures_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:lectures_manager_view')->only(['show']);
        $this->middleware('admincan_permission:lectures_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {

            // Get course ID from route parameter (if using resource route with course)
            $courseId = $request->route('lectures') ?? $request->query('course');

            $lectures = Lecture::with(['section', 'section.course'])
                ->when($courseId, function ($query) use ($courseId) {
                    $query->forCourse($courseId);
                })
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->filterByType($request->query('type'))
                ->join('course_sections', 'lectures.section_id', '=', 'course_sections.id')
                ->select('lectures.*', 'course_sections.title as section_title')
                ->orderBy('course_sections.title', 'asc')
                ->orderBy('lectures.order', 'asc')
                ->orderBy('lectures.created_at', 'desc')
                ->sortable()
                ->paginate(Lecture::getPerPageLimit())
                ->withQueryString();

            $statuses = ['draft', 'published', 'archived'];
            $types = ['video', 'audio', 'text', 'quiz'];

            // Get course info if filtering by course
            $course = $courseId ? Course::find($courseId) : null;

            return view('course::admin.lecture.index', compact('lectures', 'statuses', 'types', 'course'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load lectures: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $sections = Section::all();
            $types = ['video', 'audio', 'text', 'quiz'];
            $statuses = ['draft', 'published', 'archived'];

            return view('course::admin.lecture.createOrEdit', compact('sections', 'types', 'statuses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load lecture creation form: ' . $e->getMessage());
        }
    }

    public function store(LectureCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            // Generate slug if not provided
            if (empty($requestData['slug'])) {
                $requestData['slug'] = Str::slug($requestData['title']);
            }

            // video upload
            if ($request->hasFile('video')) {
                $requestData['video'] = $this->imageService->upload($request->file('video'), 'lecture/videos');
            }

            // attachment upload
            if ($request->hasFile('attachment')) {
                $requestData['attachment'] = $this->imageService->upload($request->file('attachment'), 'lecture/attachments');
            }

            $lecture = Lecture::create($requestData);

            // Sync section if provided
            if (isset($requestData['section_id'])) {
                $lecture->section()->associate($requestData['section_id']);
                $lecture->save();
            }

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lecture created successfully.',
                    'redirect' => route('admin.lectures.index')
                ]);
            }

            return redirect()->route('admin.lectures.index')->with('success', 'Lecture created successfully.');
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create lecture: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create lecture: ' . $e->getMessage());
        }
    }

    /**
     * show lecture details
     */
    public function show(Lecture $lecture)
    {
        try {
            $lecture->load(['section']);
            return view('course::admin.lecture.show', compact('lecture'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load lecture: ' . $e->getMessage());
        }
    }

    public function edit(Lecture $lecture)
    {
        try {
            $lecture->load(['section']);
            $sections = Section::all();
            $types = ['video', 'audio', 'text', 'quiz'];
            $statuses = ['draft', 'published', 'archived'];

            return view('course::admin.lecture.createOrEdit', compact('lecture', 'sections', 'types', 'statuses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load lecture for editing: ' . $e->getMessage());
        }
    }

    public function update(LectureUpdateRequest $request, Lecture $lecture)
    {
        try {
            $requestData = $request->validated();

            // Generate slug if not provided
            if (empty($requestData['slug'])) {
                $requestData['slug'] = Str::slug($requestData['title']);
            }

            // video upload
            if ($request->hasFile('video')) {
                $requestData['video'] = $this->imageService->upload($request->file('video'), 'lecture/videos');
            }

            // attachment upload
            if ($request->hasFile('attachment')) {
                $requestData['attachment'] = $this->imageService->upload($request->file('attachment'), 'lecture/attachments');
            }

            $lecture->update($requestData);

            // Sync section if provided
            if (isset($requestData['section_id'])) {
                $lecture->section()->associate($requestData['section_id']);
                $lecture->save();
            } else {
                $lecture->section()->dissociate();
                $lecture->save();
            }

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lecture updated successfully.',
                    'redirect' => route('admin.lectures.index')
                ]);
            }

            return redirect()->route('admin.lectures.index')->with('success', 'Lecture updated successfully.');
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update lecture: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update lecture: ' . $e->getMessage());
        }
    }

    public function destroy(Lecture $lecture)
    {
        try {
            $lecture->delete();
            return response()->json(['success' => true, 'message' => 'Lecture deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete lecture.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $lecture = Lecture::findOrFail($request->id);
            $lecture->status = $request->status;
            $lecture->save();

            $statusMap = [
                'draft' => ['label' => 'Draft', 'btnClass' => 'btn-warning', 'tooltip' => 'Click to publish or archive'],
                'published' => ['label' => 'Published', 'btnClass' => 'btn-success', 'tooltip' => 'Click to archive or draft'],
                'archived' => ['label' => 'Archived', 'btnClass' => 'btn-danger', 'tooltip' => 'Click to draft or publish'],
            ];

            $currentStatus = $lecture->status;
            $label = $statusMap[$currentStatus]['label'] ?? ucfirst($currentStatus);
            $btnClass = $statusMap[$currentStatus]['btnClass'] ?? 'btn-secondary';
            $tooltip = $statusMap[$currentStatus]['tooltip'] ?? 'Change status';
            $dataStatus = $lecture->status == "archived" ? 'published' : ($lecture->status == "draft" ? 'published' : ($lecture->status == "published" ? 'archived' : 'draft'));

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.lectures.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $lecture->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateHighlight(Request $request)
    {
        try {
            $lecture = Lecture::findOrFail($request->id);
            $lecture->is_highlight = $request->status;
            $lecture->save();

            $dataStatus = $lecture->is_highlight == '1' ? '0' : '1';
            $label = $lecture->is_highlight == '1' ? 'Yes' : 'No';
            $btnClass = $lecture->is_highlight == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $lecture->is_highlight == '1' ? 'Click to remove highlight' : 'Click to highlight';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.lectures.updateHighlight') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $lecture->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Highlight status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update highlight status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function list(Request $request)
    {
        try {
            $lectures = Lecture::with(['section', 'section.course'])
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->filterByType($request->query('type'))
                ->orderBy('created_at', 'desc')
                ->paginate(Lecture::getPerPageLimit())
                ->withQueryString();

            // Get available statuses and types for filter dropdowns
            $statuses = ['draft', 'published', 'archived'];
            $types = ['video', 'text', 'quiz', 'assignment'];

            return view('course::admin.lecture.listIndex', compact('lectures', 'statuses', 'types'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load lecture list: ' . $e->getMessage());
        }
    }
}
