@extends('app')

@section('content')

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Bina No</th>
            <th scope="col">Kat No</th>
            <th scope="col" style="text-align: center">Bu kat dışında başka kata uğrayanlar<br><small>(Aynı gün içinde birden fazla kat)</small></th>
            <th scope="col" style="text-align: center">Zıplama Yapanlar<br><small>(2 dk içinde başka kata gidip geldi mi?)</small></th>
        </tr>
        </thead>
        <tbody>
        @foreach($buildings as $build => $floor)
            @foreach($buildings[$build] as $floor)
                <tr>

                    <td>{{$build}}</td>

                    <td>{{$floor}}</td>
                    <td style="text-align: center">
                        <a href="{{route('bfkName',$build."-".$floor)}}" class="btn btn-sm btn-light">Göster</a>
                    </td>
                    <td style="text-align: center">
                        <a href="{{route('zypName',$build."-".$floor)}}" class="btn btn-sm btn-light">Göster</a>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

@endsection
