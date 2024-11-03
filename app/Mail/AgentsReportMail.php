<?php

namespace App\Mail;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgentsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $excelFile = Excel::raw(new AgentsExport, \Maatwebsite\Excel\Excel::XLSX);

        return $this->subject('Agents Report')
            ->view('emails.agents_report')
            ->attachData($excelFile, 'agents_report.xlsx', [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }
}