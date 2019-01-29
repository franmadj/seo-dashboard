<?php

namespace App\Http\Controllers;

use App\Email;
use App\ImapAccount;
use App\Contact;
use Illuminate\Http\Request;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;
use Illuminate\Support\Facades\Auth;
use Webklex\IMAP\Client;

class EmailController extends Controller {

    protected $foundEmails = [];

    /**
     * Display a listing of the email.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (true) {//8,9
            if (in_array(Auth::user()->id, [8, 9]) && \App::environment() != 'Local')
                $imapAccounts = \App\User::find(5)->imapAccounts;
            else
                $imapAccounts = Auth::user()->imapAccounts;

            $emails = \App\Email::whereIn('sender', $imapAccounts->pluck('domain')->toArray())->get();

            $emails->each(function($email) {
                $el = \App\ImapAccount::whereDomain($email->sender)->first();
                $oClient = new Client([
                    'host' => $el->imap,
                    'port' => 993,
                    'encryption' => 'ssl',
                    'validate_cert' => false,
                    'username' => $el->domain,
                    'password' => $el->password,
                    'protocol' => 'imap'
                ]);
                $this->foundEmails[$email->id]['unseen'] = 0;
                $this->foundEmails[$email->id]['email'] = $el->domain;
                $this->foundEmails[$email->id]['recipient'] = $email->recipient;
                $this->foundEmails[$email->id]['created_at'] = $email->created_at->diffForHumans();
                $this->foundEmails[$email->id]['opportunity'] = json_decode($email->Contact->opportunity->data)->url;
                $this->foundEmails[$email->id]['display'] = false;
                $this->foundEmails[$email->id]['recipients'] = [];
                $this->foundEmails[$email->id]['recipients'][] = [
                    'campaign' => '$email->Contact->opportunity->campaign->name',
                    'email' => $email->recipient,
                    'subject' => $email->subject,
                    'body' => $email->body,
                    'date' => $this->foundEmails[$email->id]['created_at']
                ];
                try {
                    if ($oClient->connect() && $el->domain != 'labrest03@gmail.com') {
                        $aFolder = $oClient->getFolders();

                        foreach ($aFolder as $oFolder) {
                            $unseen = $oFolder->query()->unseen()->from($email->recipient)->get()->count(); //var_dump($unseen);

                            $aMessageFrom = $oFolder->query()->where([['FROM', $email->recipient]])->get();
                            $aMessageTo = $oFolder->query()->where([['TO', $email->recipient]])->get();


                            $aMessage = $aMessageTo->merge($aMessageFrom);
                            if ($aMessage->count()) {


                                $this->foundEmails[$email->id]['unseen'] += $unseen;
                                //$this->foundEmails[$email->id]['campaign'] = $el->Contact->opportunity->campaign->name;
                            }

                            $i = 0;
                            foreach ($aMessage as $oMessage) {
                                if ($oMessage->hasTextBody()) {
                                    $body = $oMessage->getTextBody();
                                } elseif ($oMessage->hasHtmlBody()) {
                                    $origBody = $oMessage->getHtmlBody();
                                    preg_match("/<body[^>]*>(.*?)<\/body>/is", $origBody, $matches);
                                    $body = isset($matches[1]) ? $matches[1] : $origBody;
                                }

                                //var_dump($body);



                                $this->foundEmails[$email->id]['recipients'][] = [
                                    'campaign' => '$email->Contact->opportunity->campaign->name',
                                    'email' => $email->recipient,
                                    'subject' => $oMessage->getSubject(),
                                    'body' => $body,
                                    'date' => \Carbon\Carbon::createFromTimeStamp($oMessage->getDate()->timestamp)->diffForHumans()];
                                //$this->foundEmails[$email->id][$rec->recipient][] = ['subject' => $oMessage->getSubject(), 'body' => $oMessage->getHTMLBody(true)];
                                //echo 'Attachments: ' . $oMessage->getAttachments()->count() . '<br />';
                                //$this->foundEmails[$email->id][$rec->recipient]['body'] = $oMessage->getHTMLBody(true);
//                                    if ($i > 10)
//                                        break;
//                                    if ($i > 10)
//                                        exit;
//                                    $i++;
                            }
                        }



                        /*

                          $recipients = Email::where('sender', $el->domain)->where('type', 'outbox')->get();

                          $recipients->each(function($rec)use($aFolder, $el) {

                          foreach ($aFolder as $oFolder) {
                          $unseen = $oFolder->query()->unseen()->from($rec->recipient)->get()->count(); //var_dump($unseen);

                          $aMessageFrom = $oFolder->query()->where([['FROM', $rec->recipient]])->get();
                          $aMessageTo = $oFolder->query()->where([['TO', $rec->recipient]])->get();


                          $aMessage = $aMessageTo->merge($aMessageFrom);
                          if ($aMessage->count()) {


                          $this->foundEmails[$el->domain]['unseen'] += $unseen;
                          //$this->foundEmails[$el->domain]['campaign'] = $el->Contact->opportunity->campaign->name;
                          }

                          $i = 0;
                          foreach ($aMessage as $oMessage) {
                          if ($oMessage->hasTextBody()) {
                          $body = $oMessage->getTextBody();
                          } elseif ($oMessage->hasHtmlBody()) {
                          $origBody = $oMessage->getHtmlBody();
                          preg_match("/<body[^>]*>(.*?)<\/body>/is", $origBody, $matches);
                          $body = isset($matches[1]) ? $matches[1] : $origBody;
                          }

                          //var_dump($body);



                          $this->foundEmails[$el->domain]['recipients'][] = [
                          'campaign' => '$rec->Contact->opportunity->campaign->name',
                          'email' => $rec->recipient,
                          'subject' => $oMessage->getSubject(),
                          'body' => $body,
                          'date' => \Carbon\Carbon::createFromTimeStamp($oMessage->getDate()->timestamp)->diffForHumans()];
                          //$this->foundEmails[$el->domain][$rec->recipient][] = ['subject' => $oMessage->getSubject(), 'body' => $oMessage->getHTMLBody(true)];
                          //echo 'Attachments: ' . $oMessage->getAttachments()->count() . '<br />';
                          //$this->foundEmails[$el->domain][$rec->recipient]['body'] = $oMessage->getHTMLBody(true);
                          //                                    if ($i > 10)
                          //                                        break;
                          //                                    if ($i > 10)
                          //                                        exit;
                          //                                    $i++;
                          }
                          }
                          });

                         */
                    }
                } catch (\Exception $e) {
                    $this->foundEmails[$email->id]['error'] = $e->getMessage();
                }
            });
            $this->foundEmails = array_values($this->foundEmails);
            //var_dump(json_encode($this->foundEmails, JSON_HEX_APOS));
        } else {
            $this->foundEmails = json_decode('[{"unseen":0,"email":"fmdavodafone1@gmail.com","display":false,"recipients":[{"campaign":"name2","email":"fmc03@hotmail.es","subject":"Presupuesto","body":"\r\n<div id=\"cuerpoEmail\">\r\n\r\n\t<div id=\"emailCabecera\">\r\n    \t<img src=\"http:\/\/www.padelgalis.com\/imagenes\/main-logo.png\" width=\"200\" \/>\r\n    <\/div>\r\n    \r\n    <div id=\"emailTexto\">\r\n    <div>Email recibido por la aplicaci\u00f3n web ### PRESUPUESTO ###<\/div><br \/>\r\n    <div align=\"left\" style=\"font-size:18px\"\" >\r\n    \r\n    \r\n    \tNombre: <span style=\"font-size:14px\">nombre<\/span><br \/>\r\n        \r\n        Email: <span style=\"font-size:14px\">fmc03@hotmail.es<\/span><br \/>\r\n        \r\n        Tel\u00e9fono: <span style=\"font-size:14px\">07454008216<\/span><br \/><br \/>\r\n        \r\n        Mensaje: <span style=\"font-size:14px\">mensaje<\/span><br \/><br \/>\r\n        \r\n        Ubicaci\u00f3n del montaje: <span style=\"font-size:14px\">lugar montaje<\/span><br \/><br \/>\r\n        \r\n        Tipo de pista: <span style=\"font-size:14px\">Pista pilares<\/span><br \/><br \/>\r\n        \r\n        Cantidad de pistas: <span style=\"font-size:14px\">8<\/span><br \/><br \/>\r\n        \r\n        Tipo de pista secundario: <span style=\"font-size:14px\">Pista galis<\/span><br \/><br \/>\r\n        \r\n        Cantidad de pistas secundario: <span style=\"font-size:14px\">2<\/span><br \/><br \/>\r\n        \r\n        Lugar del montaje: <span style=\"font-size:14px\">exterior<\/span><br \/><br \/>\r\n        \r\n        Tipo c\u00e9sped: <span style=\"font-size:14px\">Fibrilado<\/span><br \/><br \/>\r\n        \r\n        Color del cesped: <span style=\"font-size:14px\">verde<\/span><br \/><br \/>\r\n        \r\n        Grosor c\u00e9sped: <span style=\"font-size:14px\">10mm<\/span><br \/><br \/>\r\n        \r\n        Tipo de iluminaci\u00f3n: <span style=\"font-size:14px\">Halogenuro<\/span><br \/><br \/>\r\n        \r\n        Tipo de foco: <span style=\"font-size:14px\">4_proyectores<\/span><br \/><br \/>\r\n        \r\n        Vidrio: <span style=\"font-size:14px\">10mm<\/span><br \/><br \/>\r\n        \r\n        Tipo de puertas: <span style=\"font-size:14px\">World Padel Tour<\/span><br \/><br \/>\r\n        \r\n        Color de la estructura: <span style=\"font-size:14px\">verde<\/span><br \/><br \/>\r\n        \r\n        Material: <span style=\"font-size:14px\">materiales standard<\/span><br \/><br \/>\r\n        \r\n        Tipo de cubierta: <span style=\"font-size:14px\">13mm<\/span><br \/><br \/>\r\n        \r\n        \r\n    <\/div>\r\n  <\/div>\r\n\r\n<\/div>\r\n","date":"3 years ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"werwe","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div><br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div>rewtse test<br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"2 days ago"},{"campaign":"name2","email":"labrest03@gmail.com","subject":"sdf","body":"","date":"2 days ago"}]},{"unseen":0,"email":"labrest03@gmail.com","display":false,"recipients":[{"campaign":"name2","email":"fmc03@hotmail.es","subject":"OruxMaps track","body":"","date":"3 years ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"Fw: dsnada","body":"\r\n<div id=\"divtagdefaultwrapper\" style=\"font-size:12pt;color:#000000;font-family:Calibri,Arial,Helvetica,sans-serif;\" dir=\"ltr\">\r\n<p><br>\r\n<\/p>\r\n<br>\r\n<br>\r\n<div style=\"color: rgb(0, 0, 0);\">\r\n<hr tabindex=\"-1\" style=\"display:inline-block; width:98%\">\r\n<div id=\"divRplyFwdMsg\" dir=\"ltr\"><font style=\"font-size:11pt\" color=\"#000000\" face=\"Calibri, sans-serif\"><b>From:<\/b> hotmail cuenta &lt;fmc03@hotmail.es&gt;<br>\r\n<b>Sent:<\/b> 09 June 2017 07:57 PM<br>\r\n<b>To:<\/b> francisco Mauri<br>\r\n<b>Subject:<\/b> ds<\/font>\r\n<div>&nbsp;<\/div>\r\n<\/div>\r\n<div>\r\n<div id=\"divtagdefaultwrapper\" dir=\"ltr\" style=\"font-size:12pt; color:#000000; font-family:Calibri,Arial,Helvetica,sans-serif\">\r\n<p><a href=\"https:\/\/spalumi.com\/f263\/evenonce-963499570-a-90002\/index4.html#post1758063\" class=\"OWAAutoLink\" id=\"LPlnk866700\" previewremoved=\"true\">https:\/\/spalumi.com\/f263\/evenonce-963499570-a-90002\/index4.html#post1758063<\/a><br>\r\n<\/p>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"1 year ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"asdfasdf","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div><br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div><br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"18 hours ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"werwer","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div><br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div><br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"18 hours ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"sdfs","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div><br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div><br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"18 hours ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"rtyrty","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div><br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div>nuevoo<br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"18 hours ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"ertert","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div>newwww<br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div><br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"18 hours ago"},{"campaign":"name2","email":"fmc03@hotmail.es","subject":"nuevo mensaje to labrest","body":"\r\n<div data-externalstyle=\"false\" dir=\"ltr\" style=\"font-family: \u0027Calibri\u0027, \u0027Segoe UI\u0027, \u0027Meiryo\u0027, \u0027Microsoft YaHei UI\u0027, \u0027Microsoft JhengHei UI\u0027, \u0027Malgun Gothic\u0027, \u0027sans-serif\u0027;font-size:12pt;\">\r\n<div>aqu\u00ed nuevo mensaje con contenido<br>\r\n<\/div>\r\n<div data-signatureblock=\"true\">\r\n<div><br>\r\n<\/div>\r\n<div>Enviado desde Correo de Windows<\/div>\r\n<div><br>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n","date":"16 hours ago"}]}]', true);
            //var_dump($this->foundEmails);
        }



        return Responder::success($this->foundEmails)->respond();
    }

    /**
     * Send emails from contacts page
     *
     * @return \Illuminate\Http\Response
     */
    public function sendEmails(Request $request) {
        try {
            //var_dump($request->all());
            if ($request->has('text') && $request->has('emails') && $request->has('imapAccount')) {
                $data = $request->all();
                //$imapAccount = $request->get('imapAccount');
                if ($imapData = ImapAccount::find($request->get('imapAccount'))) {
                    $to = [];
                    foreach ((array) $data['emails'] as $email) {
                        $parts = explode(',', $email);
                        $to[] = end($parts);
                    }

                    if (in_array(Auth::user()->id, [5]))
                        $to = ['fmdavodafone1@gmail.com'];

                    $sent = sendSmtpEmails($imapData->domain, $imapData->smtp, $imapData->password, $to, array_get($data, 'subject', 'Seo campaign'), $request->get('text'));


                    if ($sent || true) {

                        foreach ((array) $data['emails'] as $email) {
                            $parts = explode(',', $email);
                            Contact::emailSent(reset($parts), 'sent');
                            Email::create([
                                'sender' => $imapData->domain,
                                'recipient' => end($parts),
                                'subject' => array_get($data, 'subject', 'Seo campaign'),
                                'body' => $request->get('text'),
                                'contact_id' => reset($parts),
                                'type' => 'outbox'
                            ]);
                        }


                        return Responder::success()->respond();
                    } else {
                        return Responder::error('Error sending! Try again!');
                    }
                } else
                    return Responder::error('Invalid Imap Account!');
            } else
                return Responder::error('No content! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    /**
     * Reply email.
     *
     * @return \Illuminate\Http\Response
     */
    public function replyEmail(Request $request) {
        try {
            //var_dump($request->all());
            if ($request->has('text') && $request->has('email') && $request->has('imapAccount')) {
                $data = $request->all();
                if ($imapData = ImapAccount::find($request->get('imapAccount'))) {
                    $sent = sendSmtpEmails($imapData->domain, $imapData->smtp, $imapData->password, $request->get('emails'), array_get($data, 'subject', 'Seo campaign'), $request->get('text'));
                    if ($sent || true) {

                        Email::create([
                            'sender' => $imapData->domain,
                            'recipient' => $request->get('email'),
                            'subject' => array_get($data, 'subject', 'Seo campaign'),
                            'body' => $request->get('text'),
                            'type' => 'outbox'
                        ]);


                        return Responder::success()->respond();
                    } else {
                        return Responder::error('Error sending! Try again!');
                    }
                } else {
                    return Responder::error('Invalid Imap Account!');
                }
            } else
                return Responder::error('No content! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function catchEmails(Request $request) {
        try {
            \Log::info('catchEmails post: ' . print_r($request->all(), true));
            $contactId = NULL;
            if ($email = Email::whereRecipient($request->sender)->whereType('outbox')->first()) {
                $contactId = $email->contact_id;
            }
            $result = Email::create([
                        'sender' => $request->sender,
                        'recipient' => $request->recipient,
                        'subject' => $request->subject,
                        'body' => $request->get('body-plain'),
                        'contact_id' => $request->get('currentContact'),
                        'type' => 'inbox'
            ]);
            \Log::info('catchEmails create: ' . print_r($result, true));
        } catch (\Exception $e) {

            \Log::error('catchEmails: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email) {
        //
    }

    /**
     * Display a listing of the email.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexd() {
        $buildData = [];
        $emails = Email::where('id', '>', 0)->orderBy('created_at', 'ASC')->get();
        $emails->each(function($el) use(&$buildData) {

            //var_dump($el->type);
            if ($el->type == 'outbox') {
                $currentEmail = $el->recipient;
            } else {
                $currentEmail = $el->sender;
            }
            if (!isset($buildData[$currentEmail])) {
                $buildData[$currentEmail] = [];
                if (!isset($buildData[$currentEmail]['newEmails']))
                    $buildData[$currentEmail]['newEmails'] = 0;
            }

            if ($el->viewed == 0) {
                $el->viewed = 1;
                $el->save();
                $buildData[$currentEmail]['newEmails'] ++;
            }
            if ($el->Contact) {
                $buildData[$currentEmail]['campaign'] = $el->Contact->opportunity->campaign->name;
            }

            $buildData[$currentEmail]['display'] = false;
            $buildData[$currentEmail]['email'] = $currentEmail;
            $buildData[$currentEmail]['data'][] = $el;
        });

        $buildData = array_values($buildData);


        return Responder::success($buildData)->respond();
    }

}
