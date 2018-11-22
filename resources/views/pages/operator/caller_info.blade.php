@extends('layouts.app')

@section('content')
<div class="w3-center w3-white menu" style="display:table">

      <div class="w3-padding w3-white" style='text-align:center'>
        <input type="text" id="caller_name" placeholder="Caller's First Name"/>
      <hr />
      </div>
      <table class="w3-padding w3-white w3-center problem-table">
          <tr>
              <th> Caller ID </th> <th> Name </th> <th> Job Title </th> <th> Department </th> <th> Telephone Number </th> <th> Selected </th>
          </tr>
          <tr>
              <td class="number"> 1 </td> <td><a href="caller-info/Dilip">Dilip</a></td> <td> JobTitle A </td> <td> Department 1 </td> <td> 0766975752 </td><td><input type="radio" name="selected" value="1"></td>
          </tr>
          <tr>
              <td class="number"> 2 </td> <td><a href="caller-info/Emma">Emma</a></td> <td> JobTitle B </td> <td> Department 2 </td> <td> 0766975863 </td><td><input type="radio" name="selected" value="2"></td>
          </tr>
      </table>

      <br>
      <div class="w3-padding w3-white" style='text-align:center'>
        <a class="blank" href="new_call">
            <input class="menu-item w3-card w3-btn w3-row" value = "Log New Call for Selected User">
        </a><br />
      </div>
</div>
@endsection
