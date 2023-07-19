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
                <div class="col-12 text-center">
                    <a href="{{ route('schedule.edit', $menu->id) }}"
                        class="btn btn-primary d-inline-block w-25 me-5">編集</a>
                    <button type="button" class="btn btn-danger d-inline-block ms-5 w-25" data-bs-toggle="modal"
                        data-bs-target="#exampleModal" id="modal_bt">
                        削除
                    </button>
                </div>
            @endforeach
        </div>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">画像確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="" class="w-100" id="modal_image" value="">
                        <p class="m-2"><input type="text" id="pic_title" class="form-control" value=""
                                placeholder="タイトル"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">確認</button>
                        <button type="button" class="btn btn-info" id="save_title_bt"
                            data-bs-dismiss="modal">タイトル保存</button>
                        <button type="button" class="btn btn-danger" id="delete_bt" data-bs-dismiss="modal">削除</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="delete_dialog" title="削除の確認">
            <p class="text-center mb-3 text-prymary" id="d_title"></p>
            <p class="text-center">ファイルを削除してもよろしいですか</p>
        </div>
    </x-slot>
    <x-slot name="script">
        <script></script>
    </x-slot>
</x-base-layout>
