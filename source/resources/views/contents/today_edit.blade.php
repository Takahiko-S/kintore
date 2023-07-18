<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">

        </style>
    </x-slot>

    <x-slot name="main">
        <div class="container">
            <h1 class="text-center mt-5">今日のメニュー編集</h1>

            <form action="{{ route('today_update', ['id' => $menu->id]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="row col-12 mt-5">
                    <!-- Button trigger modal -->
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal" id="modal_bt">
                            種目追加
                        </button>
                    </div>

                    <div class="col-md-6 text-end mb-5">
                        <button type="submit" class="btn btn-primary w-25">更新</button>
                    </div>

                </div>

                <div class="col-md-6">
                    <h4>メニュー名</h4>
                    <input type="text" name="name" value="{{ $menu->name }}" class="form-control">
                </div>

                <!-- メニュー編集部分 -->
                @foreach ($menu->menuExercises as $index => $menuExercise)
                    <div class="exercise-block">
                        <!-- 種目を囲むdiv -->
                        <h3 class="text-start mt-5">{{ $menuExercise->exercise->name }}</h3>

                        <div class="form-group">
                            <table class="table table-bordered">
                                <!-- ラベル部分 -->
                                <thead>
                                    <tr>
                                        <th class="col-md-1 text-center">セット数</th>
                                        <th class="col-md-1 text-center">回数</th>
                                        <th class="col-md-1 text-center">重量 (kg)</th>
                                        <th class="col-md-7 text-center">メモ</th>
                                        <th class="col-md-2 text-center">操作</th>
                                    </tr>
                                </thead>

                                <!-- データ部分 -->
                                <tbody>
                                    <tr class="menu-row text-center">
                                        <input type="hidden" name="menu_exercises[{{ $index }}][id]"
                                            value="{{ $menuExercise->id }}">
                                        <input type="hidden" name="menu_exercises[{{ $index }}][exercise_id]"
                                            value="{{ $menuExercise->exercise->id }}">
                                        <td>
                            <!--ーーーーーーーーーーーーーーーーーーー nameがブラウザで表記するとおかしくなってるから修正するーーーーーーーーーーーーーーーーー -->
                                            <span class="set-number"
                                                name="menu_exercises[{{ $index }}">{{ $menuExercise->order }}</span> 
                                                
                            <!--ーーーーーーーーーーーーーーーーーーー nameがブラウザで表記するとおかしくなってるから修正するーーーーーーーーーーーーーーーーー -->
                                            <!-- セット数 -->
                                            
                                        </td>


                                        <td>
                                            <input type="number" name="menu_exercises[{{ $index }}][reps]"
                                                value="{{ $menuExercise->reps }}" class="form-control text-center">
                                            <!-- 回数 -->
                                        </td>
                                        <td>
                                            <input type="number" name="menu_exercises[{{ $index }}][weight]"
                                                value="{{ $menuExercise->weight }}" class="form-control text-center">
                                            <!-- 重量 -->
                                        </td>
                                        <td>
                                            <input type="text" name="menu_exercises[{{ $index }}][memo]"
                                                class="form-control"> <!-- メモ -->
                                        </td>
                                        <td>
                                            <form action="{{ route('today_destroy', $menuExercise->id) }}"
                                                method="POST" class="w-100">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100">削除</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-primary w-25 add-menu">セット追加</button>
                        </div>
                    </div>
                @endforeach

                <div clsss="col-12">
                    <div class="row mb-5">
                        <div class="col-md-6 text-end">

                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary mt-5 w-25">更新</button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">追加種目選択</h5>
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
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $exercise->id }}" id="exercise{{ $exercise->id }}"
                                                    name="selectedExercises[]">
                                                <label class="form-check-label" for="exercise{{ $exercise->id }}">
                                                    {{ $exercise->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach


                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn btn-info" id="add-exercises">種目追加</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>





        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->
    </x-slot>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                $(document).on('click', '.add-menu', function() {
                    var buttonRow = $(this).closest('div');
                    var exerciseBlock = buttonRow.closest('.exercise-block'); // get current exercise block
                    var newRow = exerciseBlock.find('.menu-row:first')
                        .clone(); // clone the first row of the current exercise block

                    var newIndex = exerciseBlock.find('.menu-row').length; // calculate new index

                    newRow.find('input[name^="menu_exercises"]').each(function() {
                        var newName = this.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                        this.name = newName;
                    });

                    newRow.find(
                        'input[name^="menu_exercises"][name$="[reps]"], input[name^="menu_exercises"][name$="[weight]"]'
                    ).val(''); // reset reps and weight inputs

                    // newRow.find('input[name^="menu_exercises"][name$="[id]"]').val(''); // do not reset the id input

                    newRow.find('.set-number').text(newIndex + 1); // update the set number

                    newRow.appendTo(exerciseBlock.find(
                        'tbody')); // add new row to the current exercise block's tbody
                });
            });
        </script>



    </x-slot>

</x-base-layout>
