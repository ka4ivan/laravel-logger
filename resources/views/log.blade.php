<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <title>Laravel logger</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <style>
        body {
          padding: 25px;
        }

        h1 {
          font-size: 1.5em;
          margin-top: 0;
        }

        #table-log {
            font-size: 0.85rem;
        }

        .sidebar {
            font-size: 0.85rem;
            line-height: 1;
        }

        .btn {
            font-size: 0.7rem;
        }

        .stack {
          font-size: 0.85em;
        }

        .date {
          min-width: 75px;
        }

        .text {
          word-break: break-all;
        }

        a.llv-active {
          z-index: 2;
          background-color: #f5f5f5;
          border-color: #777;
        }

        .list-group-item {
          word-break: break-word;
        }

        .folder {
          padding-top: 15px;
        }

        .div-scroll {
          height: 80vh;
          overflow: hidden auto;
        }
        .nowrap {
          white-space: nowrap;
        }
        .list-group {
            padding: 5px;
        }
        .custom-switch .custom-control-label::before {
            top: 0;
        }

        .custom-switch .custom-control-input:checked~.custom-control-label::after, .custom-switch .custom-control-label::after {
            top: 2px;
        }

        .content-data {
            white-space: pre-wrap;
            word-break: break-word;
            max-width: 100%;
            overflow-x: auto;
            color: black;
        }

        /**
        * DARK MODE CSS
        */

        body[data-theme="dark"] {
          background-color: #151515;
          color: #cccccc;
        }

        [data-theme="dark"] a {
          color: #4da3ff;
        }

        [data-theme="dark"] a:hover {
          color: #a8d2ff;
        }

        [data-theme="dark"] .list-group-item {
          background-color: #1d1d1d;
          border-color: #444;
        }

        [data-theme="dark"] a.llv-active {
            background-color: #0468d2;
            border-color: rgba(255, 255, 255, 0.125);
            color: #ffffff;
        }

        [data-theme="dark"] a.list-group-item:focus, [data-theme="dark"] a.list-group-item:hover {
          background-color: #273a4e;
          border-color: rgba(255, 255, 255, 0.125);
          color: #ffffff;
        }

        [data-theme="dark"] .table td, [data-theme="dark"] .table th,[data-theme="dark"] .table thead th {
          border-color:#616161;
        }

        [data-theme="dark"] .page-item.disabled .page-link {
          color: #8a8a8a;
          background-color: #151515;
          border-color: #5a5a5a;
        }

        [data-theme="dark"] .page-link {
          background-color: #151515;
          border-color: #5a5a5a;
        }

        [data-theme="dark"] .page-item.active .page-link {
          color: #fff;
          background-color: #0568d2;
          border-color: #007bff;
        }

        [data-theme="dark"] .page-link:hover {
          color: #ffffff;
          background-color: #0051a9;
          border-color: #0568d2;
        }

        [data-theme="dark"] .form-control {
          border: 1px solid #464646;
          background-color: #151515;
          color: #bfbfbf;
        }

        [data-theme="dark"] .form-control:focus {
          color: #bfbfbf;
          background-color: #212121;
          border-color: #4a4a4a;
        }

        [data-theme="dark"] .content-data {
            color: white;
        }
    </style>

    <script>
        function initTheme() {
            const darkThemeSelected =
            localStorage.getItem('darkSwitch') !== null &&
            localStorage.getItem('darkSwitch') === 'dark';
            darkSwitch.checked = darkThemeSelected;
            darkThemeSelected ? document.body.setAttribute('data-theme', 'dark') :
            document.body.removeAttribute('data-theme');
        }

        function resetTheme() {
          if (darkSwitch.checked) {
              document.body.setAttribute('data-theme', 'dark');
              localStorage.setItem('darkSwitch', 'dark');
          } else {
              document.body.removeAttribute('data-theme');
              localStorage.removeItem('darkSwitch');
          }
        }
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col sidebar mb-3">
                <div class="list-group div-scroll">
                    @foreach($folders as $folder)
                    <div class="list-group-item">
                        @php
                            \Ka4ivan\LaravelLogger\Support\LaravelLogViewer\LaravelLogViewer::DirectoryTreeStructure( $storage_path, $structure );
                        @endphp
                    </div>
                    @endforeach
                    @foreach($files as $file)
                    <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}" class="list-group-item @if($current_file == $file) llv-active @endif">
                        {{$file}}
                    </a>
                    @endforeach
                    <div class="custom-control custom-switch" style="padding-top:20px;">
                        <input type="checkbox" class="custom-control-input" id="darkSwitch">
                        <label class="custom-control-label" for="darkSwitch" style="margin-top: 6px; font-size: 15px;">Dark Mode</label>
                    </div>
                </div>
            </div>
            <div class="col-10 table-container">
                @if($logs === null)
                <div>
                    Log file >50M, please download it.
                </div>
                @else
                <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                    <thead>
                        <tr>
                            @if($standardFormat)
                            <th style="width: 5%;">Level</th>
                            <th style="width: 5%;">Date</th>
                            @else
                            <th style="width: 5%;">Line number</th>
                            @endif
                            <th style="width: 70%;">Content</th>
                            <th style="width: 20%;">Auth</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $key => $log)
                        @php
                            $array = json_decode($log['text'], true);
                            $message = $array['message'] ?? null;
                            $data = $array['data'] ?? $log['text'];
                            $model = $array['model'] ?? null;
                            $id = $array['id'] ?? null;
                            $url = $array['url'] ?? null;
                            $action = $array['action'] ?? null;
                            $user = json_decode($log['text'], true)['user'] ?? null;
                        @endphp
                        <tr data-display="stack{{ $key }}">
                            @if($standardFormat)
                            <td class="nowrap text-{{ $log['level_class'] }}">
                                <span class="fa fa-{{ $log['level_img'] }}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                <br>
                                <i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;&nbsp;{{ $log['context'] }}
                            </td>
                            @endif

                            <td class="date">{{ $log['date'] }}</td>

                            <td class="text">
                                @if($log['stack'] || $url)
                                <button type="button"
                                    class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                    data-display="stack{{ $key }}">
                                <span class="fa fa-search"></span>
                                </button>
                                @endif

                                @if($model)
                                    {{ "{$model} {$action} - $id" }}
                                @else
                                    {{ $message }}
                                @endif

                                @php
                                    $dataArray = is_array($data) ? $data : json_decode($data, true);
                                @endphp
                                @if(is_array($dataArray))
                                <pre class="content-data">{{ json_pretty($dataArray) }}</pre>
                                @else
                                {{ $data }}
                                @endif

                                @isset($log['in_file'])
                                  <br/>{{ $log['in_file'] }}
                                @endisset

                                @if($log['stack'] || $url)
                                <div class="stack" id="stack{{ $key }}" style="display: none; white-space: pre-wrap;">@if($url){{ json_pretty(['url' => $url]) }}<br>@endif{{  trim($log['stack'])  }}</div>
                                @endif
                            </td>

                            <td class="text">
                                @if($user)
                                <button type="button"
                                        class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                        data-display="user{{ $key }}">
                                    <span class="fa fa-user"></span>
                                </button>
                                @endif
                                @foreach(\Illuminate\Support\Arr::only($user ?? [], config('logger.user.fields')) as $field => $value)
                                    {!! "{$field} - <b>{$value}</b>" !!} <br>
                                @endforeach
                                @if($user)
                                <div class="stack" id="user{{ $key }}" style="display: none; white-space: pre-wrap;"><br><pre class="content-data">{{ json_pretty($user) }}</pre></div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                <div class="p-3">
                    @if($current_file)
                    <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-download"></span> Download file
                    </a>
                    -
                    <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-sync"></span> Clean file
                    </a>
                    -
                    <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-trash"></span> Delete file
                    </a>
                    @if(count($files) > 1)
                    -
                    <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="fa fa-trash-alt"></span> Delete all files
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery for Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <!-- FontAwesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <!-- Datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script>
        // dark mode by https://github.com/coliff/dark-mode-switch
        const darkSwitch = document.getElementById('darkSwitch');

        // this is here so we can get the body dark mode before the page displays
        // otherwise the page will be white for a second...
        initTheme();

        window.addEventListener('load', () => {
            if (darkSwitch) {
                initTheme();
                darkSwitch.addEventListener('change', () => {
                    resetTheme();
                });
            }
        });

      // end darkmode js
        $(document).ready(function () {
            $('.expand').on('click', function (event) {
                event.stopPropagation();
                var targetId = $(this).data('display');
                $('#' + targetId).toggle();
            });

            $('#table-log').DataTable({
                "order": [$('#table-log').data('orderingIndex'), 'desc'],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });

            $('#delete-log, #clean-log, #delete-all-log').click(function () {
                return confirm('Are you sure?');
            });
        });
    </script>
</body>
</html>
