<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    // This method will show jobs page
    public function index(Request $request){
        $categories = Category::where('status', 1)->get();
        $job_types = JobType::where('status', 1)->get();
        $jobs = Job::where('status', 1);
        // Search using keywords
        if (!empty($request->keywords)) {
            $jobs->where(function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->keywords . '%')
                ->orWhere('keywords', 'like', '%' . $request->keywords . '%'); // use grouping query for like with or condition
            });
        }

        // Search using location
        if (!empty($request->location)) {
            $jobs->where('location', $request->location);
        }

        // Search using location
        if (!empty($request->category)) {
            $jobs->where('category_id', $request->category);
        }

        // Search using jobType
        if (!empty($request->job_type)) {
            $arr = $request->job_type;
            $arr = explode(',', $request->job_type);
            $jobs->whereIn('job_type_id', $arr);
        }

        // Search using Experience
        if (!empty($request->experience)) {
            $jobs->where('experience', $request->experience);
        }

        $jobs->with(['jobType', 'Category']);
        // Search using sort
        if(isset($request->sort) && $request->sort == 0){
            $jobs = $jobs->orderBy('created_at', 'ASC')->paginate(9);
        }else{
            $jobs = $jobs->orderBy('created_at', 'DESC')->paginate(9);
        }
        return view('front.jobs', ['jobs'=>$jobs, 'categories'=>$categories, 'job_types'=>$job_types]);
    }

    public function detail($id){
        $job = Job::where(['id' => $id, 'status' => 1])->with(['jobType', 'user'])->first();
        //return $job;
        if($job == null){
            abort(404);
        }
        return view('front.jobdetails', ['job'=>$job]);
    }

    public function applyJob(Request $request){
        $jobID = $request->jobID;
        // If job not found
        $job = Job::where('id', $jobID)->first();
        $employer_id = $job->user_id;

        if($job == null){
            session()->flash('error', 'Job does not exist!');

            return response()->json([
                'status' => false,
                'message' => 'Job does not exist!'
            ]); 
        }

        // You can not apply twice on a job
        $job_application_count = JobApplication::where([
            'user_id' => Auth::user()->id, 
            'job_id' => $jobID
        ])->count();
        
        if($job_application_count>0){
           session()->flash('error', 'you already applied on this job!');
           return response()->json([
                'status' => false,
                'message' => 'you already applied on this job!'
            ]); 
        }

        // you can not apply your own job
        if(Auth::user()->id == $employer_id){
            session()->flash('error', 'you can not apply your own job!');

            return response()->json([
                'status' => false,
                'message' => 'you can not apply your own job!'
            ]);
        }

        $application = New JobApplication();
        $application->job_id = $jobID;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        if($application->save()){
            // Send Notification Email to Employer
            $employer = User::where('id', $employer_id)->first();
            $mailData = [
                'employer' => $employer,
                'user' => Auth::user(),
                'job' => $job,
            ];
            $employer_email = $employer->email;
            Mail::to($employer_email)->send(new JobNotificationEmail($mailData));

            session()->flash('success', 'you have successfully applied!');

            return response()->json([
                'status' => true,
                'message' => 'you have successfully applied!'
            ]);
        }else{
            session()->flash('error', 'Something went wrong!');

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function saveJob(Request $request){
        $job_id = $request->jobID;
        $job = Job::where(['id' => $job_id, 'status' => 1])->first();
        return $job;
        if($job == null){
            abort(404);
        }
    }

}
