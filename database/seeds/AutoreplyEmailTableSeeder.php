<?php

use Illuminate\Database\Seeder;
use App\AutoreplyEmail;

class AutoreplyEmailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email1 = new AutoreplyEmail;
        $email1->name = "Parent Registration Confirmation";
        $email1->url_slug = "parent-registration-confirmation";
        $email1->content = '<p>Thank you [parent_name] for registering for Brush-a-mania 2020!</p>

		<p>You and your child/children have taken on the challenge to help promote proper oral heath in your household, as well as, among your community. We thank you for your participation and hope that you have fun during the month of April – Oral Health Month tracking your brushes and flosses.</p>

		<p>You can track your child/children’s brushes and flosses just by going to <a href="https://app.brushamania.ca/login">https://app.brushamania.ca/login</a> and entering your email address and password. You have until 12am (midnight) to record the previous day’s brushes and flosses.</p>

		<p>All elementary students up to grade 6 who brush and/or floss their teeth 100 times within any 30 day period will be emailed a Brush-a-mania certificate of achievement.  His or her name will be entered into our draw to win an Xbox One or one of fifty tablets.</p>

		<p>Any elementary student in Canada up to grade 6 may enter.  The contest closes May 31, 2020.  Winners will be notified after June 7 and all winners’ first name and name of their school will be posted on our website.  Prizes will be sent to your child’s school for presentation.  We wish you all good luck and remember to have fun while you brush and floss!</p>

		<p>With our primary goal being to help promote proper oral heath, if your child happens to forget recording his/her brushes, you have the ability to restart their 30 day tracking period. Please remember the recording of brushes online begins April 1st and closes May 31st.</p>

		<p>If you have any questions, please contact my co-chair Jennifer Boyd at jennifer@brushamania.ca.</p>

		<p>Thank you so much for promoting good oral health.</p>

		<p>Dr. Raffy Chouljian, DDS<br>
		Brush-a-mania Chair</p>';
		$email1->save();

		$email2 = new AutoreplyEmail;
		$email2->name = "School Registration Confirmation";
		$email2->url_slug = "school-registration-confirmation";
		$email2->content = '<p>Thank you [school_name] for participating in Brush-a-mania 2020!</p>

		<p>This is our twentieth year of motivating students to brush and floss their teeth and promoting oral health.</p>

		<p>Your assigned dentist will contact you to arrange a date and time for your presentation(s). Once you have confirmed a date and time with your dentist, please email my co-chair Jennifer Boyd at jennifer@brushamania.ca to update your information. To find your assigned dentist, please check our website for the <a href="https://brushamania.ca/school-presentation">List of Schools Registered for Brush-a-mania 2020</a>. This School list will be updated regularly with all of the information you will require: School name; number of students; assigned dentist and telephone number; assigned Rotarian and telephone number; guests; etc. You will also find <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/School-Instructions-1.pdf">instructions</a> for your presentation day.</p>

		<p>For schools outside the City of Toronto, please ensure that your School Registration Fee is paid. For your convenience, you may <a href="https://brushamania.ca/school-presentation">pay on-line here</a>. All schools in the City of Toronto are sponsored by the Scarborough Rotary Passport Club.</p>

		<p>All of the supplies needed to run the Brush-a-mania program will be delivered directly to the schools the week of March 23. If you need to change the number of classes or student kits, please please email my co-chair Jennifer Boyd at jennifer@brushamania.ca before March 6th.</p>

		<p>Please print an article in your April school newsletter to encourage your parents to participate in Brush-a-mania and promote good oral health. A sample article is available in Word for you to download and use in <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Newsletter-Article-schools-English.doc">English</a> and in <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Newsletter-Article-schools-French.doc">French</a>.</p>

		<p>For more information or to make corrections, please contact my co-chair Jennifer Boyd at jennifer@brushamania.ca.</p>

		<p>Thank you so much for promoting good oral health.</p>

		<p>Raffy Chouljian, DDS
		Brush-a-mania Chair</p>

		<p>Brush-a-mania School registration confirmation for [school_name] <br>
		School Name:	[school_name] <br>
		Address:	[school_address] <br>
		City:	[school_city] <br>
		Postal Code:	[school_postal_code] <br>
		Phone:	[school_phone] <br>
		School Contact Name:	[school_contact] <br>
		School Contact Email:	[user_email]<br>
		Name of dentist requested:	[requested_dentist]<br>
		Number Of Classes:	[number_of_classes] <br>
		Number Of Students:	[number_of_students] <br>
		School Presentation Schedule:	[presentation_date] </p>';
		$email2->save();

		$email3 = new AutoreplyEmail;
		$email3->name = "Dentist Registration Confirmation";
		$email3->url_slug = "dentist-registration-confirmation";
		$email3->content = '<p>Thank you Dr. [dentist_name] for participating in Brush-a-mania 2020!</p>

		<p>This is our twentieth year of motivating students to brush and floss their teeth and promoting oral health.</p>

		<p>You have registered the following schools:</p>
		<p>[school_name]</p>
		<p>If your school(s) was not already on our list of registered schools, please contact your school(s) and explain the program.  Ask them to register online here.  You may use the information sheet provided.  Once you have confirmed a date and time for your Brush-a-mania presentation(s) with your school(s), please email my co-chair Jennifer Boyd at jennifer@brushamania.ca to update your information.</p>

		<p>For schools outside the City of Toronto, please ensure that your School Registration Fee is paid.  For your convenience, you may pay on-line here.  All schools in the City of Toronto are sponsored by the Scarborough Rotary Passport Club.</p>

		<p>All of the supplies needed to run the Brush-a-mania program will be delivered directly to the schools the week of March 23, 2020.</p>

		<p>Please check our website for the List of Schools Registered for Brush-a-mania [SCHOOL YEAR].  This School list will be updated regularly with all of the information you will require: School name; address; telephone number; number of students; assigned dentist; assigned Rotarian; etc.</p>

		We have several resources on our website you may use:
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Tips-on-working-with-your-public-health-unit.pdf">Tips on working with your public health unit</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Dentist-Instructions-1.pdf">Instructions for your presentation day</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Script-Dentist-1.doc">Template script in English</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Script-Dentist-in-French.doc">Template script in French</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Brush-a-mania-PPoint-English.ppt">Template PowerPoint presentation in English</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Brush-a-mania-PPoint-French-1.pptx">Template PowerPoint presentation in French</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Brush-a-mania-PPoint-Middle-Schools.pptx">Template PowerPoint presentation for Middle schools</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/How-to-make-your-own-giant-toothbrush.pdf">How to make your own giant toothbrush</a>
		<br>
		<a href="https://docs.google.com/file/d/0B5S4WQV1LgnnQUs3R3dmc0ZzNE5FaVlJTzdHMEw4MkJ0NzJB/edit">Brush-a-mania song</a>
		<br>
		<a href="https://www.youtube.com/watch?v=0KRuyePp2AI">Manitoba Dental Association - "We Be Brushin" video</a>
		<br>
		<a href="https://www.youtube.com/watch?v=mxvDny_OwE0&feature=youtu.be">Colgate Tooth Defender video</a>
		<br>
		<a href="https://www.youtube.com/watch?v=kUQ6kGqc2cU">Colgate Tooth Defender video French</a>
		<br>
		<p>The Component Societies of Kingston, Ottawa and Toronto are coordinating the media releases for their area.  If your school is located outside these cities, please help us promote Brush-a-mania and oral health by inviting the media to your presentation.  You may use the template media releases on our website:  <a href="https://brushamania.ca/wp-content/uploads/sites/2/2019/11/Press-Release-1-1.doc">Press Release 1</a>; <a href="https://brushamania.ca/wp-content/uploads/sites/2/2019/11/Press-Release-2-1.doc">Press Release 2</a>. You may also contact your Mayor to declare April Oral Health Month in your municipality:  <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Letter-Proclamation-Request.doc">Mayoral Proclamation Request</a>.</p>

		<p>Any elementary student (JK to Grade 6) anywhere in Canada may register and record each time they brush their teeth on our website.  Everyone who brushes and/or flosses their teeth 100 times within any 30 day period will be emailed a Brush-a-mania certificate of achievement.  His or her name will be entered into our draw to win an Xbox One or one of fifty tablets.  The goal is to encourage the students to develop good oral hygiene habits at an early age.  Please encourage your patients to participate by distributing these <a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Postcard.pdf">postcards</a> in your office.  Please let me know how may you would like and we will ship them for free with your school supplies.</p>

		<p>Thank you so much for promoting good oral health.</p>

		<p>You have signed up to help out on the following Brush-a-mania committees:</p>

		<p>For more information or to make corrections, contact Raffy Chouljian: raffy@brushamania.ca.</p>';
		$email3->save();

		$email4 = new AutoreplyEmail;
		$email4->name = "Dentist Confirmed For School";
		$email4->url_slug = "dentist-confirmed-for-school";
		$email4->content = '<p>Hello [school_name]</p>

		<p>Below is the contact information for the dentist that will be presenting Brush-a-mania at your school:</p>

		<p>Dentist: Dr. [dentist_name]<br>
		Clinic: [dentist_clinic]<br>
		Contact Name: [dentist_contact_name]<br>
		Work Phone: [dentist_phone]<br>
		Email: [dentist_email]<br>
		Website: [dentist_website]</p>

		<p>Please feel free to contact your dentist to set a date and time for your Brush-a-mania presentation. Once you have confirmed a date and time for your Brush-a-mania presentation(s) with your dentist, please email my co-chair Jennifer Boyd at jennifer@brushamania.ca to update your information.</p>

		<p>If you have any questions, please contact my co-chair Jennifer Boyd at jennifer@brushamania.ca.</p>

		<p>Thank you so much for promoting good oral health.</p>

		<p>Raffy Chouljian, DDS <br>
		Brush-a-mania Chair</p>';
		$email4->save();

		$email5 = new AutoreplyEmail;
		$email5->name = "School Confirmed For Dentist";
		$email5->url_slug = "school-confirmed-for-dentist";
		$email5->content = '<p>Hello Dr. [dentist_name]:</p>

		<p>You are confirmed to be presenting Brush-a-mania at [school_name]. Below is their contact information. Please discuss with the school your presentation date and time.  Once you have confirmed a date and time for your Brush-a-mania presentation(s), please email my co-chair Jennifer Boyd at jennifer@brushamania.ca to update your information.</p>

		<p>School: [school_name]</p>

		<p>Address: [school_address]<br>
		City: [school_city]<br>
		Postal Code: [school_postal_code]<br>
		Phone: [school_phone]<br>
		School contact: [school_contact]<br>
		Email: [school_email]</p>



		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Tips-on-working-with-your-public-health-unit.pdf">Tips on working with your public health unit</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Dentist-Instructions-1.pdf">Instructions for your presentation day</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Script-Dentist-1.doc">Template script in English</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Script-Dentist-in-French.doc">Template script in French</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Brush-a-mania-PPoint-English.ppt">Template PowerPoint presentation in English</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Brush-a-mania-PPoint-French-1.pptx">Template PowerPoint presentation in French</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/Brush-a-mania-PPoint-Middle-Schools.pptx">Template PowerPoint presentation for Middle schools</a>
		<br>
		<a href="https://brushamania.ca/wp-content/uploads/sites/2/2020/01/How-to-make-your-own-giant-toothbrush.pdf">How to make your own giant toothbrush</a>
		<br>
		<a href="https://docs.google.com/file/d/0B5S4WQV1LgnnQUs3R3dmc0ZzNE5FaVlJTzdHMEw4MkJ0NzJB/edit">Brush-a-mania song</a>
		<br>
		<a href="https://www.youtube.com/watch?v=0KRuyePp2AI">Manitoba Dental Association - "We Be Brushin" video</a>
		<br>
		<a href="https://www.youtube.com/watch?v=mxvDny_OwE0&feature=youtu.be">Colgate Tooth Defender video</a>
		<br>
		<a href="https://www.youtube.com/watch?v=kUQ6kGqc2cU">Colgate Tooth Defender video French</a>

		<p>If you have any questions, please contact my co-chair Jennifer Boyd at jennifer@brushamania.ca.</p>

		<p>Thank you so much for promoting good oral health.</p>

		<p>Raffy Chouljian, DDS <br>
		Brush-a-mania Chair</p>';
		$email5->save();

		$email6 = new AutoreplyEmail;
		$email6->name = "School Invitation";
		$email6->url_slug = "school-invitation";
		$email6->content = '<p>Hello [school_name], Dr. has requested that your school be part of the Brush-a-mania program this year. <a href="http://app.brushamania.ca/school-registration?invite_token=[invite_token]">Please register here</a>.</p>

		<p>Join us as we Celebrate 20 Years of Brush-a-mania
		April is a very special month in Canada.  It is Oral Health Month! What better way to recognize Oral Health Month than to have your students participate in a fun and educational program.</p>

		<p>Brush-a-mania is a not-for-profit program designed to promote oral health and awareness among young children from Junior Kindergarten to grade 6.  It was started in 2001 by the Scarborough Rotary Passport Club and has already reached over 800,000 students.  Our goal is to educate and motivate children and to bring together dentists, Rotarians, teachers and parents to create a celebration around proper dental care.</p>

		<p>If you choose to participate, your school would hold an assembly during the month of April, where a dentist and a Rotarian will come into the school and provide the children with an interactive, entertaining presentation in proper dental care. Each student will receive a Colgate toothbrush and toothpaste, a Brush-a-mania instruction pamphlet and a Brush-a-mania sticker.  The assembly will conclude with a Brush-off where all participants “brush their teeth” simultaneously for 3 minutes.  (Optional)</p>

		<p>The students are encouraged to track their brushes for 30 days in the classroom and at home online.  When the students complete the online program, they will be be emailed a Brush-a-mania certificate of achievement.   His or her name will be entered into our draw to win an Xbox One or one of fifty tablets.</p>

		<p>I hope that you agree Brush-a-mania will benefit your students and will consider this program for your school.  If you have any questions please contact my co-chair Jennifer Boyd at jennifer@brushamania.ca.</p>

		<p>Thank you so much for promoting good oral health.</p>

		<p>Raffy Chouljian, DDS<br>
		Brush-a-mania Chair</p>';
		$email6->save();

    }
}
