<x-base-layout> 
<x-slot name="title">筋トレスケジュール</x-slot>
 <x-slot name="css">
<style>
/* 必要なCSSをここに書く */
</style>
</x-slot> 

<x-slot name="main">
<div class="container">
	<h2 class="mt-4 text-center">筋トレスケジュール</h2>
	<div class="col-12 text-end mt-5">
		<button type="button" class="btn btn-primary d-inline-block ms-5 w-25"
			data-bs-toggle="modal" data-bs-target="#newModal">メニュー追加
		</button>
	</div>
	@foreach ($menus as $menuIndex => $menu)
	<div class="row">
		<div class="col-12 text-start">
			<h4 class="mt-3">メニュー名：{{ $menu->name }}</h4>
		</div>
	</div>

	@if ($menu->menuExercises)
	<table class="table" style="table-layout: fixed;">
		<thead>
			<tr>
				<th class="text-center" style="width: 20%;">Ｎｏ</th>
				<th class="text-center" style="width: 50%;">種目名</th>
				<th style="width: 12.5%;">重量</th>
				<th style="width: 12.5%;">回数</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($menu->menuExercises as $workoutIndex => $menuExercise)
			<tr>
				<th class="text-center" scope="row">{{ $workoutIndex + 1 }}</th>
				<td class="text-center" style="width: 50%;">{{$menuExercise->exercise->name }}</td>
				<td class="text-center" style="width: 12.5%;">{{	$menuExercise->weight }}</td>
				<td class="text-center" style="width: 12.5%;">{{ $menuExercise->reps }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@else
	<p class="mt-2">このメニューには内容が登録されていません。</p>
	@endif
	<div class="col-12 text-center mb-5">
		<a href="{{ route('schedule.edit', $menu->id) }}" class="btn btn-primary d-inline-block w-25 me-5">編集</a>
		<button type="button" class="btn btn-danger d-inline-block ms-5 w-25"
			data-bs-toggle="modal" data-bs-target="#deleteModal"	data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->name }}">削除
		</button>


	</div>
		@endforeach
	 <!--ーーーーーーーーーーーーーーーーーーーーーーーーーー削除モーダルーーーーーーーーーーーーーーーーーーーー-->
	<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalLabel">削除の確認</h5>
				</div>
				<div class="modal-body">
					<p class="text-center" id="menuName"></p>
					<p class="text-center">このメニューを削除してもよろしいですか？</p>
				</div>
				<div class="modal-footer">
					<div class="mx-auto">
						<button type="button" class="btn btn-secondary"	data-bs-dismiss="modal">キャンセル</button>
						<button type="button" class="btn btn-danger ms-5" id="confirmDelete">削除する</button>
					</div>
				</div>
			</div>
		</div>
	</div>
   <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーメニュー追加モーダルーーーーーーーーーーーーーーーーーーーー-->

        <!-- Modal -->
        <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newModalLabel">新メニュー作成</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="exercises-form" action="{{ route('add_exercise') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                            @foreach ($exercises as $body_part => $exercises_in_body_part)
                                <div>
                                    <h5>{{ $body_part }}</h5>
                                    <div class="d-flex flex-wrap justify-content-start">
                                        @foreach ($exercises_in_body_part as $exercise)
                                            <div class="form-check m-2">
                                                <input class="form-check-input" type="checkbox" value="{{ $exercise->id }}" 
                                                id="exercise{{ $exercise->id }}" name="selectedExercises[]">
                                                <label class="form-check-label" for="exercise{{ $exercise->id }}">
                                                    {{ $exercise->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach


    						<div class="d-flex justify-content-between">
    							<div>
        							<button type="button" class="btn btn-secondary"	data-bs-dismiss="modal">閉じる</button>
        							<button type="submit" class="btn btn-info" id="add-exercises">メニュー作成</button>
    							</div>
    							<button type="submit" class="btn btn-primary" id="new-exercises">新しい種目を追加する</button>
    						</div>
						</form>
                    </div>
                </div>
            </div>
            

        </div>





        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->
</x-slot>

 <x-slot name="script">
  <script>
     $('#deleteModal').on('show.bs.modal', function (event) {
    	  var button = $(event.relatedTarget) // Button that triggered the modal
    	  var menuId = button.data('menu-id') // Extract info from data-* attributes
    	  var menuName = button.data('menu-name') // Extract info from data-* attributes

    	  // Set the menu details in the modal
    	  var modal = $(this)
    	  modal.find('.modal-body #menuName').text(menuName)
    	  modal.find('.modal-footer #confirmDelete').data('menuId', menuId)
    	})

    	$('#confirmDelete').on('click', function() {
    	  var menuId = $(this).data('menuId');
    	  deleteMenu(menuId);
    	  // Send AJAX DELETE request to your server here
    	});

    	function deleteMenu(menuId){
    	  // Set the cursor to 'wait' to indicate that the request is being processed
    	  document.body.style.cursor = 'wait';

    	  // Set up the data to be sent in the AJAX request
    	  var fd = new FormData();
    	  fd.append("_token", $('meta[name=csrf-token]').attr('content')); // Add CSRF token
    	  fd.append("menuId", menuId); // Add the menu id

    	  // Send the AJAX request
    	  $.ajax({
    	    url: "./menu_delete",  // Endpoint where the menu will be deleted
    	    type:"POST",
    	    data:fd,
    	    processData: false,
    	    contentType:false,
    	    timeout: 10000,
    	    error:function(XMLHttpRequest, textStatus, errorThrown){
    	      console.error(XMLHttpRequest, textStatus, errorThrown);
    	      document.body.style.cursor = 'auto';
    	    }
    	  })
    	  .done(function(res){
    	    // Reload the page when the request is successful
    	    location.reload();
    	  })
    	  .fail(function(res) {
    	    // Handle error here
    	    console.error(res);
    	  });
    	}





        </script> </x-slot> </x-base-layout>
