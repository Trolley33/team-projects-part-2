@extends('layouts.app')

@section('content')

    <div class="w3-center menu">
        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>Problem Related Tasks</h3><hr />
            <a class="blank" href="http://cort.sci-project.lboro.ac.uk/team4/problems/view_all.php">
                <div class="menu-item w3-card w3-button w3-row">
                    View All Problems
                </div>
            </a><br />
        </div>
        <br />
        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>View Problems by specialist</h3><hr />
            <table class="w3-padding w3-white w3-center problem-table">
                <tr>
                    <th> Caller ID </th> <th> Name </th> <th> Job Title </th> <th> Telephone Number </th> <th></th>
                </tr>
                <tr  class="w3-hover-light-grey approve">
                  <td class="number"> 3 </td> <td>Bert</td> <td> JobTitle A </td> <td> 0766975752 </td> <td><a href="../problems/problem_by_specialist.php?name=Bert&id_no=3"><button class="w3-button w3-teal">View</button></a></td>
                </tr>
                <tr  class="w3-hover-light-grey approve">
                  <td class="number"> 4 </td> <td>Clara</td> <td> JobTitle B </td> <td> 0766975863 </td> <td><a href="../problems/problem_by_specialist.php?name=Clara&id_no=4"><button class="w3-button w3-teal">View</button></a></td>
                </tr>
            </table>
        </div>
        <br />
        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>View Problems by Software</h3><hr />
            <table class="w3-padding w3-white w3-center problem-table">
                <tr>
                    <th> Software ID </th> <th> Name </th> <th> Type </th> <th></th>
                </tr>
                <tr  class="w3-hover-light-grey approve">
                  <td class="number"> 123 </td> <td> Lorem ipsum </td> <td> Word Processing </td> <td><a href="../problems/problem_by_software_hardware.php?name=Lorem+ipsum&id_no=123"><button class="w3-button w3-teal">View</button></a></td>
                </tr>
                <tr  class="w3-hover-light-grey approve">
                  <td class="number"> 456 </td> <td> dolor sit amet </td> <td> Spreadsheet </td> <td><a href="../problems/problem_by_software_hardware.php?name=dolor+sit+amet&id_no=456"><button class="w3-button w3-teal">View</button></a></td>
                </tr>
            </table>
        </div>
        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>View Problems by Hardware</h3><hr />
            <table class="w3-padding w3-white w3-center problem-table">
                <tr>
                    <th> Serial Number </th> <th> Type </th> <th> Make </th> <th></th>
                </tr>
                <tr  class="w3-hover-light-grey approve">
                  <td class="number"> 321 </td> <td> Mouse </td> <td> Lorem ipsum </td> <td><a href="../problems/problem_by_software_hardware.php?name=Lorem+ipsum&id_no=321"><button class="w3-button w3-teal">View</button></a></td>
                </tr>
                <tr  class="w3-hover-light-grey approve">
                  <td class="number"> 654 </td> <td> Keyboard </td> <td> dolor sit amet </td> <td><a href="../problems/problem_by_software_hardware.php?name=dolor+sit+amet&id_no=654"><button class="w3-button w3-teal">View</button></a></td>
                </tr>
            </table>
        </div>
    </div>

@endsection
