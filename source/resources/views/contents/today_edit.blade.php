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
                                                <button type="button" class="btn btn-danger w-100 delete-button"
                                                    data-id="{{ $menuExercise->id }}">削除</button>
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
            // ---------------------メニュー追加ボタンを押したときの処理--------------------------------------
            // 1. ページのDOMが全て読み込まれたら、以下の処理を開始します。
            $(document).ready(function() {

                // 2. '.add-menu'というクラスを持つ要素がクリックされたときに以下の処理を実行します。
                $(document).on('click', '.add-menu', function() {

                    // 3. クリックした要素から最も近いdiv要素を取得します。
                    var buttonRow = $(this).closest('div');

                    // 4. buttonRowから最も近い'.exercise-block'というクラスを持つ要素を取得します。
                    var exerciseBlock = buttonRow.closest('.exercise-block');

                    // 5. exerciseBlock内の最初の'.menu-row'というクラスを持つ要素を複製します。
                    var newRow = exerciseBlock.find('.menu-row:first').clone();

                    // 6. exerciseBlock内の'.menu-row'というクラスを持つ要素の数を取得します。
                    var newIndex = exerciseBlock.find('.menu-row').length;

                    // 7. 新しい行の各入力欄のname属性を更新します。この際、元のインデックスを新しいインデックスに置き換えます。
                    newRow.find('input[name^="menu_exercises"]').each(function() {
                        var newName = this.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                        this.name = newName;
                    });

                    // 8. 新しい行のセット数、重量、メモの入力欄をリセットします。
                    newRow.find(
                        'input[name^="menu_exercises"][name$="[reps]"], input[name^="menu_exercises"][name$="[weight]"], input[name^="menu_exercises"][name$="[memo]"]'
                    ).val('');

                    // 9. 新しい行に'new-row'というクラスを追加します。
                    newRow.addClass('new-row');

                    // 10. newRowをexerciseBlock内の'tbody'の最後に追加します。
                    newRow.appendTo(exerciseBlock.find('tbody'));

                    // 11. exerciseBlock内の最後のセット数を取得します。
                    var lastSetNumber = exerciseBlock.find('.menu-row:last .set-number').text();

                    // 12. 新しいセット数を計算します。もし最後のセット数がNaNなら1に、そうでなければ最後のセット数に1を足します。
                    var newSetNumber = isNaN(parseInt(lastSetNumber)) ? 1 : parseInt(lastSetNumber) + 1;

                    // 13. 新しいセット数を新しい行のセット数欄に設定します。
                    newRow.find('.set-number').text(newSetNumber);

                    // 14. newRowをexerciseBlock内の'tbody'の最後に追加します。
                    newRow.appendTo(exerciseBlock.find('tbody'));
                });
            });


            // ---------------------メニュー追加ボタンを押したときの処理--------------------------------------

            // ----------------------------------------削除Ajax--------------------------------------------
            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault(); // デフォルトのフォーム送信を防ぐ

                var row = $(this).closest('.menu-row'); // ボタンが含まれる行を取得

                // 新規追加された行の場合はAjaxリクエストを送らずに行を削除する
                if (row.hasClass('new-row')) {
                    row.remove();
                    return;
                }
                var id = $(this).data('id'); // ボタンのdata-id属性からIDを取得

                $.ajax({
                    url: '/today_destroy/' + id, // 適切なURLに修正してください
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRFトークン
                        _method: 'DELETE' // DELETEメソッドを指定
                    },
                    success: function(response) {
                        // 削除が成功した後の処理。例えば、該当する行をDOMから削除
                        // $(this).closest('.menu-row').remove(); など
                        location.href = '/today_edit/' + response.menu_id; // 適切なURLに修正してください
                    }
                });

            });



            // ----------------------------------------削除Ajax--------------------------------------------
        </script>



    </x-slot>

</x-base-layout>
