@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
  <h2>Select Problem Type To Add as Skill</h2>
  <!-- Name of user skill is being added for -->
  <h3>Adding Skill for: <span class="editbutton modalOpener" value="/users/{{$user->id}}/compact">{{$user->forename}} {{$user->surname}}</span></h3>
  <form id="addProblemTypeForm">
  <!-- List of problems types, where you can choose what problem type the skill is related to -->
  <table id='problem-table' class="display cell-border stripe hover">
    <thead>
      <tr>
        <th>Problem Type ID</th><th>Problem Type Name</th><th>Select</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($problem_types as $pt)
      <tr>
        <td style="text-align: right;">{{sprintf('%04d', $pt->id)}}</td>
        <td title="View" class="editbutton modalOpener" value='/problem_types/{{$pt->id}}/compact'>
          @if (!is_null($pt->pID))
            ({{$pt->pDesc}})
          @endif
          {{$pt->description}}
        </td>
        <td title="Select" class="selectBox editbutton" style="text-align: center;">
          <input class="selectRadio" type="radio" name='ptype' value="{{$pt->id}}" />
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="text-align: center;">
        <input id="addProblemType" class="bigbutton w3-card w3-button w3-row" type="submit" value="Choose Problem Type" disabled/>
    </div>
    </form>
</div>

<script>
$(document).ready( function ()
{
    //Enable button if a problem type is selected
    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addProblemType').prop('disabled', false);
    });
  //Enable button if a problem type is changed
  $('input:radio[name="ptype"]').change(
      function(){
        $('#addProblemType').prop('disabled', false);
    });
    //Form submit action
    $('#addProblemTypeForm').submit(function ()
    {
        window.location.href = '/skills/{{$user->id}}/create/' + $("input[name='ptype']:checked").val();

        return false;
    });
    var table = $('#problem-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});

</script>

@endsection
