@extends('front.layouts.app')
@section('title')
Find Best Job
@endsection
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Post a Job</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            @include('front.account.sidebar')
            <div class="col-lg-9">
                @if(Session::has('success'))
                    <x-alert type="success" message="{{ session('success') }}"/>
                @elseif(Session::has('error'))
                    <x-alert type="danger" message="{{ session('error') }}"/>   
                @endif     
                <div class="card border-0 shadow mb-4">
                    <form id="createJobForm" method="post">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" id="title" name="title" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Job Type<span class="req">*</span></label>
                                    <select name="job_type" id="job_type" class="form-select">
                                        <option value="">Select Job Type</option>
                                        @if($job_types->isNotEmpty())
                                            @foreach ($job_types as $job_type)
                                                <option value="{{ $job_type->id }}">{{ $job_type->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Salary</label>
                                    <input type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="location" id="location" name="location" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="form-control" name="description" id="description" cols="5" rows="5" placeholder="Description"></textarea>
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Benefits</label>
                                <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Responsibility</label>
                                <textarea class="form-control" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Qualifications</label>
                                <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-select form-control">
                                        <option value="">Select Experience</option>
                                        <option value="1">1 Year</option>
                                        <option value="2">2 Years</option>
                                        <option value="3">3 Years</option>
                                        <option value="4">4 Years</option>
                                        <option value="5">5 Years</option>
                                        <option value="6">6 Years</option>
                                        <option value="7">7 Years</option>
                                        <option value="8">8 Years</option>
                                        <option value="9">9 Years</option>
                                        <option value="10">10 Years</option>
                                        <option value="10_plut">10+ Years</option>
                                    </select>
                                    <p></p>
                            </div>
                            

                            <div class="mb-4">
                                <label for="" class="mb-2">Keywords</label>
                                <input type="text" placeholder="keywords" id="keywords" name="keywords" class="form-control">
                            </div>

                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <p></p>
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location</label>
                                    <input type="text" placeholder="Location" id="company_location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Website</label>
                                <input type="text" placeholder="Website" id="website" name="company_website" class="form-control">
                            </div>
                        </div> 
                        <div class="card-footer  p-4">
                            <button class="btn btn-primary">Save Job</button>
                        </div>
                    </form>
                </div>               
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom')
<script>
$('#createJobForm').submit(function(e){
    e.preventDefault();
    $.ajax({
        url: "{{ route('account.saveJob')}}",
        type: 'post',
        data: $('#createJobForm').serializeArray(),
        dataType: 'json',
        success: function(response){
            console.log(response);
            $('#title').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#job_type').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#vacancy').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#location').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#description').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#experience').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            $('#company_name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
            if(response.status == false){
                var error = response.errors;
                if(error.title){
                    $('#title').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.title);
                }
                if(error.category){
                    $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.category);
                }
                if(error.job_type){
                    $('#job_type').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.job_type);
                }
                if(error.vacancy){
                    $('#vacancy').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.vacancy);
                }
                if(error.location){
                    $('#location').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.location);
                }
                if(error.description){
                    $('#description').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.description);
                }
                if(error.experience){
                    $('#experience').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.experience);
                }
                if(error.company_name){
                    $('#company_name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.company_name);
                }
            }else if(response.status == true){
                window.location.href= "{{ route('account.myJobs') }}";
            }
        },
        error: function(xhr){
            alert('error');
            console.log("Error:", xhr.responseJSON);
        }
    })
});
</script>
@endpush