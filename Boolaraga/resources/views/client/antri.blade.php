@extends('layouts.app')
@section('title', 'Cari Antrian')
@section('styles')
<style>
  a:hover {
    text-decoration: none;
  }

  .antri {
    box-sizing: border-box;
    border: 2px solid #858796;
    width: 100%;
    height: 120px;
    display: flex;
  }
</style>
@endsection
@section('content')
<div class="row justify-content-center">
  <div class="col-12" style="margin-top: -15px">
    <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
    <div class="row mt-2">
      @for ($i = 1; $i <= $lapangan->jumlah; $i++)
        @php
        $array = array('antri' => 'K' . $i, 'jadwal' => $data['id'], 'fasilitas' => $data['fasilitas']);
        $cekData = json_encode($array);
        @endphp
        @if ($lapangan->antri($cekData) != null)
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
          <a href="{{ route('pesan', ['antri' => 'K' . $i, 'data' => Crypt::encrypt($data)]) }}">
            <div class="antri bg-white">
              <div class="font-weight-bold text-primary m-auto" style="font-size: 26px;">{{ $i }}</div>
            </div>
          </a>
        </div>
        @else
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
          <div class="antri" style="background: #858796">
            <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">K{{ $i }}</div>
          </div>
        </div>
        @endif
        @endfor
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
  }
</script>
@endsection