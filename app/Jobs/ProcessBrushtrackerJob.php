<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ChildParent;
use App\User;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BrushamaniaWinnersMail;

class ProcessBrushtrackerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $children;
    protected $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($children, $year)
    {
        $this->children = $children;
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->children as $child) {
			$child_parent = ChildParent::find($child->child_parent_id);
			$parent_user = User::find($child_parent->user_id);
			$parent_users[] = $parent_user;
			$filename = Str::slug($child->firstname.' '.$child->lastname.' '.$this->year);
			$data = [
				'firstname' => $child->firstname,
				'lastname' => $child->lastname,
                'year' => $this->year
			];
			$pdf = PDF::loadView('pdf.certificate', $data);
			$pdf->setPaper('a4')->save('pdf/'.$filename.'.pdf');
    }
}
}