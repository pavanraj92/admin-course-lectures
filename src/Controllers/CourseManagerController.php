<?php

namespace admin\courses\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\courses\Requests\CourseCreateRequest;
use admin\courses\Requests\CourseUpdateRequest;
use admin\courses\Models\Course;
use admin\categories\Models\Category;
use admin\tags\Models\Tag;
use Illuminate\Support\Str;
use admin\admin_auth\Services\ImageService;

class CourseManagerController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('admincan_permission:courses_manager_list')->only(['index']);
        $this->middleware('admincan_permission:courses_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:courses_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:courses_manager_view')->only(['show']);
        $this->middleware('admincan_permission:courses_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $courses = Course::with(['categories', 'courseTags'])
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->filterByLevel($request->query('level'))
                ->filterByLanguage($request->query('language'))
                ->sortable()
                ->latest()
                ->paginate(15)
                ->withQueryString();

            $statuses = ['pending', 'approved', 'rejected'];
            $levels = ['beginner', 'intermediate', 'advanced', 'expert'];
            $languages = Course::distinct()->pluck('language')->filter()->toArray();

            return view('course::admin.index', compact('courses', 'statuses', 'levels', 'languages'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load courses: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            $tags = Tag::all();
            $levels = ['beginner', 'intermediate', 'advanced', 'expert'];
            $statuses = ['pending', 'approved', 'rejected'];

            return view('course::admin.createOrEdit', compact('categories', 'tags', 'levels', 'statuses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load course creation form: ' . $e->getMessage());
        }
    }

    public function store(CourseCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            // Generate slug if not provided
            if (empty($requestData['slug'])) {
                $requestData['slug'] = Str::slug($requestData['title']);
            }

            //thumbnail_image upload
            if ($request->hasFile('thumbnail_image')) {
                $requestData['thumbnail_image'] = $this->imageService->upload($request->file('thumbnail_image'), 'course');
            }

            // promo_video upload
            if ($request->hasFile('promo_video')) {
                $requestData['promo_video'] = $this->imageService->upload($request->file('promo_video'), 'course');           
            }

            $course = Course::create($requestData);          

            // Sync categories if provided
            if (isset($requestData['categories'])) {
                $course->categories()->sync($requestData['categories']);
            }

            // Sync tags if provided
            if (isset($requestData['course_tags'])) {
                $course->courseTags()->sync($requestData['course_tags']);
            }

            // Handle course sections
            if (isset($requestData['sections']) && is_array($requestData['sections'])) {
                foreach ($requestData['sections'] as $sectionData) {
                    if (!empty($sectionData['title'])) {
                        $course->sections()->create([
                            'title' => $sectionData['title'],
                            'slug' => Str::slug($sectionData['title'])
                        ]);
                    }
                }
            }

            return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create course: ' . $e->getMessage());
        }
    }

    /**
     * show course details
     */
    public function show(Course $course)
    {
        try {
            $course->load(['categories', 'courseTags', 'sections']);
            return view('course::admin.show', compact('course'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load course: ' . $e->getMessage());
        }
    }

    public function edit(Course $course)
    {
        try {
            $course->load(['categories', 'courseTags', 'sections']);
            $categories = Category::all();
            $tags = Tag::all();
            $levels = ['beginner', 'intermediate', 'advanced', 'expert'];
            $statuses = ['pending', 'approved', 'rejected'];

            return view('course::admin.createOrEdit', compact('course', 'categories', 'tags', 'levels', 'statuses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load course for editing: ' . $e->getMessage());
        }
    }

    public function update(CourseUpdateRequest $request, Course $course)
    {
        try {
            $requestData = $request->validated();

            // Generate slug if not provided
            if (empty($requestData['slug'])) {
                $requestData['slug'] = Str::slug($requestData['title']);
            }

            //thumbnail_image upload
            if ($request->hasFile('thumbnail_image')) {
                $course['thumbnail_image'] = $this->imageService->upload($request->file('thumbnail_image'), 'course/images');
            }

            // promo_video upload
            if ($request->hasFile('promo_video')) {
                $course['promo_video'] = $this->imageService->upload($request->file('promo_video'), 'course/videos');
            }

            $course->update($requestData);            

            // Sync categories if provided
            if (isset($requestData['categories'])) {
                $course->categories()->sync($requestData['categories']);
            } else {
                $course->categories()->sync([]);
            }

            // Sync tags if provided
            if (isset($requestData['course_tags'])) {
                $course->courseTags()->sync($requestData['course_tags']);
            } else {
                $course->courseTags()->sync([]);
            }

            // Handle course sections
            if (isset($requestData['sections']) && is_array($requestData['sections'])) {
                // Get existing sections
                $existingSections = $course->sections()->pluck('id', 'id')->toArray();
                $updatedSectionIds = [];

                foreach ($requestData['sections'] as $sectionData) {
                    if (!empty($sectionData['title'])) {
                        if (isset($sectionData['id']) && in_array($sectionData['id'], $existingSections)) {
                            // Update existing section
                            $section = $course->sections()->find($sectionData['id']);
                            $section->update([
                                'title' => $sectionData['title'],
                                'slug' => Str::slug($sectionData['title'])
                            ]);
                            $updatedSectionIds[] = $sectionData['id'];
                        } else {
                            // Create new section
                            $newSection = $course->sections()->create([
                                'title' => $sectionData['title'],
                                'slug' => Str::slug($sectionData['title'])
                            ]);
                            $updatedSectionIds[] = $newSection->id;
                        }
                    }
                }

                // Delete sections that were removed
                $sectionsToDelete = array_diff($existingSections, $updatedSectionIds);
                if (!empty($sectionsToDelete)) {
                    $course->sections()->whereIn('id', $sectionsToDelete)->delete();
                }
            } else {
                // If no sections provided, delete all existing sections
                $course->sections()->delete();
            }

            return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update course: ' . $e->getMessage());
        }
    }

    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return response()->json(['success' => true, 'message' => 'Course deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete course.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $course = Course::findOrFail($request->id);
            $course->status = $request->status;
            $course->save();

            // Set label and button class based on course status
            $statusMap = [
                'pending' => ['label' => 'Pending', 'btnClass' => 'btn-warning', 'tooltip' => 'Click to approve or reject'],
                'approved' => ['label' => 'Approved', 'btnClass' => 'btn-success', 'tooltip' => 'Click to set as pending or reject'],
                'rejected' => ['label' => 'Rejected', 'btnClass' => 'btn-danger', 'tooltip' => 'Click to set as pending or approve'],
            ];

            $currentStatus = $course->status;
            $label = $statusMap[$currentStatus]['label'] ?? ucfirst($currentStatus);
            $btnClass = $statusMap[$currentStatus]['btnClass'] ?? 'btn-secondary';
            $tooltip = $statusMap[$currentStatus]['tooltip'] ?? 'Change status';
            $dataStatus = $course->status == "rejected" ? 'approved' : ($course->status == "pending" ? 'approved' : ($course->status == "approved" ? 'rejected' : 'pending'));

            // You may want to provide available next statuses for the frontend to handle
            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.courses.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $course->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateHighlight(Request $request)
    {
        try {
            $course = Course::findOrFail($request->id);
            $course->is_highlight = $request->status;
            $course->save();

            // create status html dynamically        
            $dataStatus = $course->is_highlight == '1' ? '0' : '1';
            $label = $course->is_highlight == '1' ? 'Yes' : 'No';
            $btnClass = $course->is_highlight == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $course->is_highlight == '1' ? 'Click to remove highlight' : 'Click to highlight';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.courses.updateHighlight') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $course->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Highlight status updated to ' . $label, 'strHtml' => $strHtml]);            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update highlight status.', 'error' => $e->getMessage()], 500);
        }
    }
}
