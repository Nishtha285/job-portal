@extends('front/layouts/app')
@section('title')
    Find Best Jobs
@endsection
@section('main')
    <section class="section-3 py-5 bg-2 ">
        <div class="container">     
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find Jobs</h2>  
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control">
                            <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : ''}}>Latest</option>
                            <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : ''}}>Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form name="searchForm" id="searchForm">
                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keywords</h2>
                                <input type="text" value = "{{ Request::get('keywords') }}" placeholder="Keywords" name="keywords" id="keyword" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Location</h2>
                                <input type="text" value = "{{ Request::get('location') }}" name="location" id="location" placeholder="Location" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                            <option {{ (Request::get('category') == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif    
                                </select>
                            </div>                   

                            <div class="mb-4">
                                <h2>Job Type</h2>
                                @if($job_types->isNotEmpty())
                                    @foreach($job_types as $job_type)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input"{{ (in_array($job_type->id, explode(',', Request::get('job_type')))) ? 'checked' : '' }} name="job_type" type="checkbox" value="{{ $job_type->id }}" id="">    
                                            <label class="form-check-label" for="">{{ $job_type->name }}</label>
                                        </div>
                                    @endforeach
                                @endif        
                            </div>

                            <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="">Select Experience</option>
                                    <option value="1" {{ (Request::get('experience') == 1) ? 'selected' : ''}}>1 Year</option>
                                    <option value="2" {{ (Request::get('experience') == 2) ? 'selected' : ''}}>2 Years</option>
                                    <option value="3" {{ (Request::get('experience') == 3) ? 'selected' : ''}}>3 Years</option>
                                    <option value="4" {{ (Request::get('experience') == 4) ? 'selected' : ''}}>4 Years</option>
                                    <option value="5" {{ (Request::get('experience') == 5) ? 'selected' : ''}}>5 Years</option>
                                    <option value="6" {{ (Request::get('experience') == 6) ? 'selected' : ''}}>6 Years</option>
                                    <option value="7" {{ (Request::get('experience') == 7) ? 'selected' : ''}}>7 Years</option>
                                    <option value="8" {{ (Request::get('experience') == 8) ? 'selected' : ''}}>8 Years</option>
                                    <option value="9" {{ (Request::get('experience') == 9) ? 'selected' : ''}}>9 Years</option>
                                    <option value="10" {{ (Request::get('experience') == 10) ? 'selected' : ''}}>10 Years</option>
                                    <option value="10_plus" {{ (Request::get('experience') == '10_plus') ? 'selected' : ''}}>10+ Years</option>
                                </select>
                            </div>    
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('jobs') }}" class="btn btn-secondary mt-3">Reset</a>               
                        </div>
                    </form> 
                </div>
                <div class="col-md-8 col-lg-9 ">
                    <div class="job_listing_area">                    
                        <div class="job_lists">
                            <div class="row">
                                @if($jobs->isNotEmpty())
                                    @foreach($jobs as $job)
                                        <div class="col-md-4">
                                            <div class="card border-0 p-3 shadow mb-4">
                                                <div class="card-body">
                                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                                    <p>{{ Str::words($job->description, $words=10, '...') }}</p>
                                                    <div class="bg-light p-3 border">
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                            <span class="ps-1">{{ $job->location }}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                            <span class="ps-1">{{ $job->jobType->name }}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                            <span class="ps-1">{{ $job->category->name }}</span>
                                                        </p>
                                                        @if(!is_null($job->salary))
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">{{ $job->salary }}</span>
                                                            </p>
                                                        @endif    
                                                    </div>

                                                    <div class="d-grid mt-3">
                                                        <a href="{{ route('jobDetail', $job->id) }}" class="btn btn-primary btn-lg">Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="card border-0 shadow p-5 text-center">
                                            <h3 class="text-danger mb-3">Job Not Found 😔</h3>
                                            <p class="mb-4">Please try different keywords, category or job type.</p>

                                            <a href="{{ route('jobs') }}" class="btn btn-primary">
                                                Reset Filters
                                            </a>
                                        </div>
                                    </div>
                                @endif  
                                {{ $jobs->appends(request()->query())->links() }}                    
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection
@push('custom')
    <script>
        $('#searchForm').submit(function(e){
            e.preventDefault();

            var url = '{{ route("jobs") }}?';

            // if keyword has value
            var keyword = $('#keyword').val();
            if(keyword !=''){
                url += '&keywords='+keyword;
            }

            var location = $('#location').val();
            if(location !=''){
                url += '&location='+location;
            }

            var category = $('#category').val();
            if(category !=''){
                url += '&category='+category;
            }

            var experience = $('#experience').val();
            if(experience !=''){
                url += '&experience='+experience;
            }

            let checkJobTypes = $('input[name="job_type"]:checked').map(function(){
                return $(this).val();
                // return 'job_type=' + this.value 
            }).get();

            if(checkJobTypes.length > 0){
                url += '&job_type='+checkJobTypes;
            }

            var sort = $('#sort').val();
            url += '&sort='+sort;
            
            window.location.href=url;
            
        });

        $('#sort').change(function(){
            $('#searchForm').submit();
        });
    </script>
@endpush