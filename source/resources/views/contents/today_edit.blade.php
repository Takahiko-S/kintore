<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">

        </style>
    </x-slot>

    <x-slot name="main">
        <div class="container">
            <h1>今日のメニュー編集</h1>

            <form action="{{ route('today_update', ['id' => $menu->id]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <div class="col-md-6">
                        <label>メニュー名</label>
                        <input type="text" name="name" value="{{ $menu->name }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary mt-5 w-25">更新</button>
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
                                        <th class="col-md-1">セット数</th>
                                        <th class="col-md-1">回数</th>
                                        <th class="col-md-1">重量 (kg)</th>
                                        <th class="col-md-7">メモ</th>
                                        <th class="col-md-2">操作</th>
                                    </tr>
                                </thead>

                                <!-- データ部分 -->
                                <tbody>
                                    <tr class="menu-row text-center">
                                        <td>
                                            <span class="set-number"
                                                name="menu_exercises[{{ $index }}">{{ $menuExercise->order }}</span>
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
                            <button type="button" class="btn btn-primary w-25 add-menu">メニュー追加</button>
                        </div>
                    </div>
                @endforeach

                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary mt-5 w-25">更新</button>
                </div>

            </form>
        </div>


    </x-slot>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                $(document).on('click', '.add-menu', function() {
                    var buttonRow = $(this).closest('div');
                    var exerciseBlock = buttonRow.closest('.exercise-block'); // 現在の種目ブロックを取得
                    var newRow = exerciseBlock.find('.menu-row:first').clone(); // 現在の種目ブロックの最初の行を複製

                    var newIndex = exerciseBlock.find('.menu-row').length; // calculate new index

                    // Update the name attribute with the new index
                    newRow.find('input[name^="menu_exercises"]').each(function() {
                        var newName = this.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                        this.name = newName;
                    });

                    newRow.find(
                        'input[name^="menu_exercises"][name$="[reps]"], input[name^="menu_exercises"][name$="[weight]"], input[name^="menu_exercises"][name$="[memo]"]'
                    ).val(''); // Reset reps, weight and memo inputs

                    // Update the set number
                    var lastSetNumber = exerciseBlock.find('.menu-row:last .set-number')
                        .text(); // 現在の種目ブロック内の最後の行のセット数を取得
                    var newSetNumber = isNaN(parseInt(lastSetNumber)) ? 1 : parseInt(lastSetNumber) +
                        1; // NaNをチェックして1を設定するか、前のセット数に1を足す
                    newRow.find('.set-number').text(newSetNumber); // 新しいセット数を設定

                    newRow.appendTo(exerciseBlock.find('tbody')); // 現在の種目ブロックのtbodyに新しい行を追加
                });
            });
        </script>



    </x-slot>

</x-base-layout>
