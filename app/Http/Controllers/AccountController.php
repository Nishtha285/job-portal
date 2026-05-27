<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class AccountController extends Controller
{
    // This method will show user registration page
    public function registration(){
        return view('front.account.registration');
    }

    // This method will save a user
    public function processRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); // 🔐 always hash password
            $user->save();

            session()->flash('success', 'You have registerd successfully.');
            
            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'errors' => ''
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // This method will show user login page
    public function login(){
        return view('front.account.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error', 'Either Email & Password is incorrect');
        }
        }else {
            return redirect()->route('account.login')
                    ->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile(){
        $id=Auth::user()->id;
        $user = User::find($id);
        return view('front.account.profile', compact('user'));
    }

    public function profileUpdate(Request $request){
        $id=Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'mobile' => 'required|numeric|digits:10',
            'designation' => 'required'
        ]);

        if($validator->passes()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success', 'Profile updated successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'Profile update successful',
                'errors' => ''
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateProfilePic(Request $request){
        $id=Auth::user()->id;
        $validateUser = Validator::make(
            $request->all(),
            [
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validateUser->errors()
            ]);
            
        }

        $img = $request->file('image');
        $ext = $img->getClientOriginalExtension();
        $imageName = $id.'_'.time(). '.' .$ext;
        $img->move(public_path().'/profile_pic', $imageName);

        // Upload thumb image
        $sourcePath = public_path().'/profile_pic/'. $imageName;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourcePath); // ✅ uploaded file read karo
        // resize / crop
        $image->cover(150, 150);
        $image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

        // Delete old file
        File::delete(public_path().'/profile_pic/'. Auth::user()->image);
        File::delete(public_path().'/profile_pic/thumb/'. Auth::user()->image);

        User::where('id', $id)->update(['image' => $imageName]);
        session()->flash('success', 'Profile picture updated successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function createJob(){
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $job_types = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        return view('front.account.job.create', ['categories'=>$categories, 'job_types'=>$job_types]);
    }

    public function saveJob(Request $request){
        $rules = [
            'title' => 'required|min:5|max:50',
            'category' => 'required',
            'job_type' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:70',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $job = New Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_type;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

            session()->flash('success', 'Job created successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'Job created successfully',
                'errors' => ''
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function myJob(){
        $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(5);
        return view('front.account.job.myjob', compact('jobs'));

        // $job_types = Job::where('user_id', Auth::user()->id)->get();
        // foreach($job_types as $job_type){
        //     $jobType[$job_type->id] = $job_type->name;
        // }
        // return view('front.account.job.myjob', ['jobs'=>$jobs, 'jobType'=>$jobType]);
    }

    public function editJob($id){
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $job_types = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();
        if($job == null){
            abort(404);
        }
        return view('front.account.job.edit',  ['categories'=>$categories, 'job_types'=>$job_types, 'job'=>$job]);
    }

    public function updateJob(Request $request, $id){
        $rules = [
            'title' => 'required|min:5|max:50',
            'category' => 'required',
            'job_type' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:70',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_type;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

            session()->flash('success', 'Job Updated successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'Job Updated successfully',
                'errors' => ''
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function deleteJob(Request $request){
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobID
        ])->first();
        //return $job;
        if($job == null){
            session()->flash('error', 'Either job deleted or not found!');
            return response()->json([
                'status' => false
            ]);
        }
        Job::where('id', $request->jobID)->delete();
        session()->flash('success', 'Job deleted successfully!');
        return response()->json([
            'status' => true,
            'message' => 'Deleted successfully!'
        ]);
    }

    public function removeJobs(Request $request){
        $job = JobApplication::where([
            'user_id' => Auth::user()->id,
            'id' => $request->id
        ])->first();
        //return $job;
        if($job == null){
            session()->flash('error', 'Either job removed or not found!');
            return response()->json([
                'status' => false
            ]);
        }
        JobApplication::where('id', $request->id)->delete();
        session()->flash('success', 'Job removed successfully!');
        return response()->json([
            'status' => true,
            'message' => 'Removed successfully!'
        ]);
    }

    public function myJobApplication(){
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)->with(['job', 'job.jobType', 'job.applications'])->paginate(5);
        // $jobApplications = JobApplication::where('user_id', Auth::user()->id)->with(['job.jobApplications'])->get();
        if($jobApplications == null){
            abort(404);
        }
        return view('front.account.job.appliedjob', ['jobApplications'=>$jobApplications]);
    }

    public function savedJobs(){
        $savedJobs = SavedJob::where('user_id', Auth::user()->id)->with(['job', 'job.jobType', 'job.applications'])->get();
        if($savedJobs == null){
            abort(404);
        }
        return view('front.account.job.savedjob', ['savedJobs'=>$savedJobs]);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
