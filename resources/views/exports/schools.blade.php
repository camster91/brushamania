<table>
    <thead>
    <tr>
        <th>
            @if($year != 0)
                Registration Date
            @else
                Last Year School Participated
            @endif
        </th>
        <th>School Name</th>
        <th>School Address</th>
        <th>School City</th>
        <th>School Province</th>
        <th>School Postal Code</th>
        <th>School Telephone</th>
        <th>Link to School Map</th>
        <th>School Contact</th>
        <th>School Email</th>
        <th>Total Number of Classes</th>
        <th>Total Number of Students</th>
        <th>Requested Presentation Date</th>
        <th>Presentation Date</th>
        <th>School Fee Paid</th>
        <th>Requested Dentist</th>
        <th>Assigned Dentist</th>
        <th>Dentist Contact</th>
        <th>Dentist Telephone</th>
        <th>Dentist Email</th>
        <th>Assigned Rotarian</th>
        <th>Rotarian Telephone</th>
        <th>Rotarian Email</th>
        <th>Rotary Club</th>
        <th>Name of Guests</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($schools as $school)
        @php

            $guests = "";
            if(!is_null($school->presentation)) {
                foreach($school->presentation->guests as $i => $guest) {
                    if($i < count($school->presentation->guests) - 1) {
                        $guests .= $guest->name.", ";
                    } else {
                        $guests .= $guest->name;
                    }
                }
            }

            $is_paid = "No";
            if(strtolower($school->postal_code[0]) == 'm') {
                $is_paid = "Toronto School";
            } elseif(isset($school->presentation->is_paid))  {
                $is_paid = $school->presentation->is_paid ? "Paid" : "Not paid";
            }
        @endphp
        <tr>
            <td>{{$year != 0 ? (!is_null($school->presentation) ? $school->presentation->created_at : "") : $school->last_registration_year}}</td>
            <td>@if(!empty($school->school_name))
                    {{$school->school_name}}
                @else
                    School name is empty
                @endif</td>
            <td>{{$school->address}}</td>
            <td>{{$school->city}}</td>
            <td>{{$school->province}}</td>
            <td>{{$school->postal_code}}</td>
            <td>{{$school->phone}}</td>
            <td><a href="https://www.google.com/maps/search/{{$school->school_slug}}">https://www.google.com/maps/search/{{$school->school_slug}}</a></td>
            <td>{{$school->contact}}</td>
            <td>{{$school->email}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->total_classes : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->total_students : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->requested_presentation_date : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->presentation_date : ""}}</td>
            <td>{{$is_paid}}</td>
            <td>{{$school->requested_dentist}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->dentist : ""}}</td>
            <td>{{!is_null($school->presentation) ? (!is_null($school->presentation->dentist_user) ? $school->presentation->dentist_contact_salutation.' '.$school->presentation->dentist_user->firstname.' '.$school->presentation->dentist_user->lastname : "") : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->dentist_phone : ""}}</td>
            <td>{{!is_null($school->presentation) ? (!is_null($school->presentation->dentist_user) ? $school->presentation->dentist_user->email : "") : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->rotarian : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->rotarian_phone : ""}}</td>
            <td>{{!is_null($school->presentation) ? (!is_null($school->presentation->rotarian_user) ? $school->presentation->rotarian_user->email : "") : ""}}</td>
            <td>{{!is_null($school->presentation) ? $school->presentation->rotary_club : ""}}</td>
            <td>{{$guests}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
