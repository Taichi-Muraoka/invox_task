{{------------------------------------------ 
    button 検索
  --------------------------------------------}}

  @props(['caption' => '', 'sampleCopy' => false])

  {{-- disabledは検索中に非活性にするため --}}
  <button type="button" class="btn btn-success" v-on:click="@if($sampleCopy)btnSearchSampleCopy @else btnSearch @endif" v-bind:disabled="disabledBtnSearch">
    <i class="fas fa-search"></i>
    @if (empty($caption)){{ 'Search' }}@else{{ $caption }}@endif
  </button>