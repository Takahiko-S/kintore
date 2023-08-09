<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">

        </style>
    </x-slot>
    <x-slot name="main">
        <div class="container">
            @if (Session::has('message'))
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            @endif

            @if ($menu)
                <div class="row align-items-center mt-5">
                    <div class="col-12">
                        <h1>{{ \Carbon\Carbon::now()->format('Y/m/d') }}: {{ $menu->name }}</h1>
                    </div>

                    <div class="col-md-3">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#confirmModal">
                            メニュー完了
                        </button>
                    </div>

                </div>
                <p>{{ $menu->description }}</p>
                <h2>Exercises</h2>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('complete_menu', ['id' => $menu->id]) }}" method="POST">
                    @csrf
                    @foreach ($menu->menuExercises->groupBy('exercise_id') as $exerciseId => $menuExercisesForExercise)
                        @php
                            $allSetsCompleted = $menuExercisesForExercise->every(function ($menuExercise) {
                                return !$menuExercise->histories->isEmpty();
                            });
                        @endphp
                        <div style="background-color: {{ $loop->iteration % 2 == 0 ? '#f8f9fa' : '#e9ecef' }};">
                            <div class="row align-items-center mt-5">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <h3>{{ $menuExercisesForExercise->first()->exercise->name }}</h3>
                                    @if (!$allSetsCompleted)
                                        <button type="submit" class="btn btn-success me-2 mt-2">Complete</button>
                                    @endif
                                </div>

                                <div class="col-md-12 text-end">
                                    @csrf
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">セット</th>
                                                <th scope="col">Reps</th>
                                                <th scope="col">Weight (kg)</th>
                                                <th scope="col">完了</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($menuExercisesForExercise as $menuExercise)
                                                @if ($menuExercise->histories->isEmpty())
                                                    <tr class="text-center">
                                                        <th scope="row">{{ $menuExercise->set }}</th>
                                                        <td>{{ $menuExercise->reps }}</td>
                                                        <td>{{ $menuExercise->weight }}</td>
                                                        <td><input type="checkbox" name="completed_exercises[]"
                                                                value="{{ $menuExercise->id }}"></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </form>


                <div class="col-10 mx-auto mt-5 ">
                    <a href="{{ route('today_edit', ['id' => $menu->id]) }}"
                        class="btn btn-primary btn-lg w-100">種目の変更</a>
                </div>
            @else
                <h1>メニューがありません。</h1>
            @endif
        </div>

        <!----------------------------------------------- Modal ----------------------------------------------------------------->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        メニューを完了しますか？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <form action="{{ route('today_complete', ['id' => $menu->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">完了する</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </x-slot>







    <x-slot name="script"></x-slot>

</x-base-layout>
