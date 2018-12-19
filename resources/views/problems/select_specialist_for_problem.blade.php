@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
  <h2>Select Specialist For New Problem</h2>
  <h3>Creating New Problem for: {{$user->forename}} {{$user->surname}}</h3>
  <h3>Problem Type: @if ($parent->description != '0') ({{$parent->description}}) @endif{{$problem_type->description}}</h3>
  {!! Form::open(['action' => 'ProblemController@store', 'method' => 'POST']) !!}
  <table id='specialist-table' class="display cell-border stripe hover" style="width:100%;">
    <thead>
      <tr>
        <th>Employee ID</th><th>Specialist Name</th><th>Problem Specialism</th><th>Select</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($specialists as $s)
      <tr>
        <td title="View" class="editbutton">{{sprintf('%04d', $s->employee_id)}}</td>
        <td title="View" class="editbutton modalOpener" value='/users/{{$s->id}}/compact'>{{$s->forename}} {{$s->surname}}</td>
                <td title="View" class="editbutton modalOpener" value='/problem_types/{{$s->pID}}/compact'>
                    @if ($s->parent_description != '0')
                        ({{$s->parent_description}})
                    @endif
                    {{$s->description}}</td>
        <td title="Select" class="selectBox editbutton" style="text-align: center;">
          <input class="selectRadio" type="radio" name='specialist' value="{{$s->id}}" />
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <div style="text-align: center;"> 
    {{Form::hidden('desc', $problem_description)}}
    {{Form::hidden('notes', $problem_notes)}}
    {{Form::hidden('importance', $problem_importance)}}
    {{Form::hidden('user_id', $user->id)}}
    {{Form::hidden('problem_type_id', $problem_type->id)}}

    {{Form::submit('Assign Problem to You', ['class'=> "menu-item w3-card w3-button w3-row", 'name'=>'submit', 'style'=>'width: 400px;'])}}
    <br />
    {{Form::submit('Assign Specialist', ['class'=> "menu-item w3-card w3-button w3-row", 'id'=>'addSpecialist', 'name'=>'submit', 'style'=>'width: 400px;', 'disabled'])}}

    {!! Form::close() !!}
  </div>
    </form>
</div>

<div id="myModal" class="modal">
</div>

<style>
.editbutton:hover
{
  background-color: #BBBBBB !important;
  cursor: pointer;
}
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* The Close Button */
.close {
  color: #bbb;
  float: right;
  margin-right: 10px;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

<script>

var modal;

$(document).ready( function () 
{

    var problem_type = <?php echo json_encode($problem_type); ?>;
    var parent = <?php echo json_encode($parent); ?>;


    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addSpecialist').prop('disabled', false);
    });
    
  modal = $('#myModal');
  $(".modalOpener").click(function() {
    $.get(
        $(this).attr('value'),
        function (data) {
            modal.html(data);
            $('#myModal div').first().prepend('<span onclick="closeModal()" class="close">&times;</span>')
        }
    );

      modal.show();
  });

  $(window).click(function(event) {
    var target = $(event.target);

    if (!target.hasClass('modalOpener'))
    {
          if (target.closest('.modal div').length == 0)
      {
        closeModal();
      }
    }
  });

  $('input:radio[name="specialist"]').change(
      function(){
        $('#addSpecialist').prop('disabled', false);
    });

    $('input:radio[name="specialist"]').each(function (i, r)
    {
      var radio = $(r);
      if (radio.val() == problem_type.id)
      {
        radio.prop('checked', true);
        $('#addSpecialist').prop('disabled', false);
      }
    });
    
    var table = $('#specialist-table').DataTable({
        order: [['2', 'asc']]
    });
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search');?>";
    
    if (search == '')
    {
        search = parent.description;
    }
    table.search(search).draw();



});

function closeModal ()
{
  modal.html('');
  modal.hide();
}


</script>

@endsection
