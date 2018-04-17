         <!-- Blog Search Well -->
		<div class="well" style="padding:5px;">
         <ul class="list-group" style="margin-bottom:0px;">
            <li class="list-group-item"> <a href="{{url('.')}}">Home</a> </li>
            <li class="list-group-item"> <a href="{{url('newArticles')}}"> New Articles </a> </li>
            <li class="list-group-item"> <a href="{{url('topArticles')}}"> Most Viewed Articles </a> </li>
            @if(Auth::check())
               <li class="list-group-item"> <a href="{{url('saved_articles')}}"> Saved Articles </a> </li>
            @endif
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

