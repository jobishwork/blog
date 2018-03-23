         <!-- Blog Search Well -->
		<div class="well" style="padding:5px;">

         <ul class="list-group" style="margin-bottom:0px;">
            <li class="list-group-item"> <a href="">Home</a> </li>
            <li class="list-group-item"> <a href=""> New Articles </a> </li>
            <li class="list-group-item"> <a href=""> Top Articles </a> </li>
            <li class="list-group-item"> <a href=""> Saved Articles </a> </li>
         </ul>

<!-- 			<form method="post" action="{{url('blog/search')}}">
			{{ csrf_field() }}
				<h4>Search</h4>
				<div class="input-group">
					<input placeholder="search here" @if(Request::input('q')) value="{{Request::input('q')}}" @endIf type="text" name="q" class="form-control">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="submit">Search</button>
					</span>
				</div>
			</form> -->
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
<!--          <div class="well">
            <h4>Recent Articles</h4>
            <ul class="list-group">
               @foreach($posts as $post_array)
                  <li class="list-group-item"> <a href="{{url('blog/'.$post_array->id)}}">{{$post_array->title}}</a></li>
               @endforeach
            </ul>
         </div> -->
<!--          <div class="well">
            <h4>Recent Comments</h4>
            <ul class="list-group">
               @foreach($comments as $comment_array)
               <li class="list-group-item"> {{$comment_array->comment}} <a href="{{url('blog/user/'.$comment_array->user->id)}}"><i>{{$comment_array->user->name}}</i></a> </li>
               @endforeach
            </ul>
         </div> -->
