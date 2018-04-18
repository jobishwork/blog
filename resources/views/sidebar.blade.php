@if(Auth::user())

<div class="well" >
                @if(Auth::user()->profile_photo)
                   <img class="img-rounded img-responsive" src="{{ url('files/user/profile_photo/resized/'.Auth::user()->profile_photo) }}">
                @else
                   <img class="img-rounded img-responsive" src="{{ url('images/default-user.png') }}">
                @endif
                <br>
                <a class="btn btn-block btn-default">{{Auth::user()->name}}</a>
            </div>

            <div class="well">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ url('/followers/'.Auth::user()->id) }}" class="btn  btn-primary">
                            {{Auth::user()->followers->count()}}
                            <br>
                            Followers
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a href="{{ url('/followings/'.Auth::user()->id) }}" class="btn btn-primary">
                            {{Auth::user()->following->count()}}
                            <br>
                            Following
                        </a>
                    </div>
                </div>
            </div>

            <div class="well" style="padding:5px;">
                <ul class="list-group" style="margin-bottom:0px;">
                    <li class="list-group-item"> <a href="{{url('newArticles')}}"> New Articles </a> </li>
                    <li class="list-group-item"> <a href="{{url('topArticles')}}"> Most Viewed Articles </a> </li>
                    <li class="list-group-item"> <a href="{{url('saved_articles')}}"> Saved Articles </a> </li>
                    <li class="list-group-item"> <a href="{{url('blog/user/'.Auth::user()->id)}}"> My Articles </a> </li>
                </ul>
            </div>
            <div class="well">
                <div class="row">
                    <div class="col-lg-12">
                        <div align="center">
                        <label>My Interest</label>
                        </div>
                        <ul style="padding-left:20px;">
                            @foreach($favorite_categories as $favorite_category)
                            <li>
                                <a href="{{url('blog/category/'.$favorite_category->id)}}">{{$favorite_category->category}}</a>
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ url('/favorite-category')}}" class="btn btn-default btn-block">Manage Interest</a>
                    </div>
                </div>
            </div>


@else
<div class="well" style="padding:5px;">
    <ul class="list-group" style="margin-bottom:0px;">
        <li class="list-group-item"> <a href="{{url('.')}}">Home</a> </li>
        <li class="list-group-item"> <a href="{{url('newArticles')}}"> New Articles </a> </li>
        <li class="list-group-item"> <a href="{{url('topArticles')}}"> Most Viewed Articles </a> </li>
    </ul>
</div>
<div class="well">
    <div class="row">
        <div class="col-lg-12">
            <ul style="padding-left:20px;">
                @foreach($categories as $category_array)
                <li>
                    <a href="{{url('blog/category/'.$category_array->id)}}">{{$category_array->category}}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<div class="well">
    <div class="row">
        <div class="col-lg-12">
            <ul style="padding-left:20px;">
                <li>
                    <a href="{{url('privacy-policy')}}">Privacy Policy</a>
                </li>
                <li>
                    <a href="{{url('terms')}}">Terms</a>
                </li>
            </ul>
        </div>
    </div>
</div>
