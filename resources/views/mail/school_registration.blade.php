<p>Thank you {{$school->name}} for participating in Brush-a-mania 2020!</p>

<p>This is our twentieth year of motivating students to brush and floss their teeth and promoting oral health.</p>

<p>Your assigned dentist will contact you to arrange a date and time for your presentation(s). Once you have confirmed a date and time with your dentist, please email my co-chair Jennifer Boyd at jennifer@brushamania.ca to update your information. To find your assigned dentist, please check our website for the <a href="https://brushamania.ca/school-presentation">List of Schools Registered for Brush-a-mania 2020</a>. This School list will be updated regularly with all of the information you will require: School name; number of students; assigned dentist and telephone number; assigned Rotarian and telephone number; guests; etc. You will also find <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/School-Instructions-1.pdf">instructions</a> for your presentation day.</p>

<p>For schools outside the City of Toronto, please ensure that your School Registration Fee is paid. For your convenience, you may <a href="https://brushamania.ca/school-presentation">pay on-line here</a>. All schools in the City of Toronto are sponsored by the Scarborough Rotary Passport Club.</p>

<p>All of the supplies needed to run the Brush-a-mania program will be delivered directly to the schools the week of March 23. If you need to change the number of classes or student kits, please please email my co-chair Jennifer Boyd at jennifer@brushamania.ca before March 6th.</p>

<p>Please print an article in your April school newsletter to encourage your parents to participate in Brush-a-mania and promote good oral health. A sample article is available in Word for you to download and use in <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Newsletter-Article-schools-English.doc">English</a> and in <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Newsletter-Article-schools-French.doc">French</a>.</p>

<p>For more information or to make corrections, please contact my co-chair Jennifer Boyd at jennifer@brushamania.ca.</p>

<p>Thank you so much for promoting good oral health.</p>

<p>Raffy Chouljian, DDS
Brush-a-mania Chair</p>

<p>Brush-a-mania School registration confirmation for {{$school->name}} <br>
School Name:	{{$school->name}} <br>
Address:	{{$school->address}} <br>
City:	{{$school->city}}, {{$school->province}} <br>
Postal Code:	{{$school->postal_code}} <br>
Phone:	{{$school->phone}} <br>
School Contact Name:	{{$school->contact_salutation}} {{$user->firstname}} {{$user->lastname}} <br>
School Contact Email:	{{$user->email}} <br>
Name of dentist requested:	{{$school->requested_dentist}}<br>
Number of Classes:	{{$presentation->number_of_classes}} <br>
Number of Students:	{{$presentation->number_of_students}} <br>
School Presentation Schedule:	{{$presentation_date}} </p>