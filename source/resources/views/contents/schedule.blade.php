<x-base-layout> <x-slot name="title">筋トレスケジュール</x-slot> <x-slot
	name="css">
<style>
/* 必要なCSSをここに書く */
</style>
</x-slot> <x-slot name="main">
<div class="container">
	<h2 class="mt-4 text-center">筋トレスケジュール</h2>
	@foreach ($menus as $menuIndex => $menu)
	<div class="row">
		<div class="col-12">
			<h4 class="mt-4">{{ $menu->name }}</h4>
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
				<td class="text-center" style="width: 50%;">{{ $menuExercise->exercise->name }}</td>
				<td class="text-center" style="width: 12.5%;">{{ $menuExercise->weight }}</td>
				<td class="text-center" style="width: 12.5%;">{{ $menuExercise->reps }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@else
	<p class="mt-2">このメニューには内容が登録されていません。</p>
	@endif
	<div class="col-12 text-right">
		<a href="{{ route('schedule.edit', $menu->id) }}"
			class="btn btn-primary mb-2">編集</a>
	</div>
	@endforeach
</div>
</x-slot> <x-slot name="script"> <script></script> </x-slot> </x-base-layout>
