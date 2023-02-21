<?php

namespace Paralelo28\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Exception;

class CustomerServices extends RootController
{

    function contactWithUs(Request $request){
        $destination = htmlspecialchars($request['destination']);
        $email = htmlspecialchars($request['email']);
        $content = htmlspecialchars($request['message']);

        if(!$destination || !$email || !$content) return abort(404);

        switch ($destination){
            case 'CustomerAttention':
                $subject = 'Atención al Cliente';
                $target = 'jacobo@editorialparalelo28.com';
                break;
            case 'Design':
                $subject = 'Diseño y edición';
                $target = 'design@editorialparalelo28.com';
                break;
            case 'Distribution':
                $subject = 'Distribución';
                $target = 'jacobo@editorialparalelo28.com';
                break;
            case 'TechnicalSupport':
                $subject = 'Soporte Técnico';
                $target = 'technicalSupport@editorialparalelo28.com';
                break;
            default:
                return abort(404);
        }

        if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
            if(strlen($content) < 1)  return Redirect::back()->withErrors('El mensaje esta vacío.');

            try{
                $data = [
                    "email" => $email,
                    "name" => explode('@', $email)[0],
                    "subject" =>  $subject,
                    "content" => $content,
                    "target" => $target
                ];

                Mail::send('emails.customerService', $data, function($message) use ($data) {
                    $message->to($data['target'], $data['name'])->subject($data["subject"]);
                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                });

                return Redirect::back()->with('successMessage', 'Su mensaje ha sido enviado, nuestro equipo de '.strtolower($subject).' la atenderá tan pronto como le sea posible.');

            }catch (Exception $e){
                return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
            }
        }else{
            return Redirect::back()->withErrors('El email es inválido.');
        }
    }

    function workWithUs(Request $request){
        $name = htmlspecialchars($request['name']);
        $surnames = htmlspecialchars($request['surnames']);
        $email = htmlspecialchars($request['email']);
        $phone = htmlspecialchars($request['phone']);
        $position = htmlspecialchars($request['position']);

        if(!$request->hasFile('CV')) return Redirect::back()->withErrors('Debe subir un curriculum vitae valido.');

        try{
            $file = $request->file('CV');
            $extension =  $file->extension();
        }catch (Exception $e){
            return Redirect::back()->withErrors('Ha sucedido un error inesperado.');
        }

        if(!$name || !$surnames || !$email || !$position) return abort(404);

        if($extension != 'pdf' && $extension != 'doc' && $extension != 'docx') return Redirect::back()->withErrors('El Curriculum debe subirse en formato PDF, DOC o DOCX');

        if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
            if(strlen($name) < 1 || strlen($surnames) < 1 || strlen($position) < 1)  return Redirect::back()->withErrors('Debe completar todos los campos requeridos.');

            try{
                if(!$phone || strlen($phone == 0)) $phone = null;

                $data = [
                    "email" => $email,
                    "fullName" => $name.' '.$surnames,
                    "subject" => 'Solicitud de trabajo',
                    "phone" => $phone,
                    "position" => $position,
                    "file" => $file,
                    "extension" => $extension
                ];

                Mail::send('emails.workWithUs', $data, function($message) use ($data) {
                    $message->to('enviatucvcanarias@gmail.com', 'Envía tu CV Canarias')->subject($data["subject"]);
                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                    $message->attach($data['file'],
                        [
                            'as' => $this->validateStringForFileName($data['fullName'])."CV.".$data['extension'],
                        ]);
                });

                return Redirect::back()->with('successMessage', 'Su solicitud de trabajo ha sido enviada, 
                    nuestro equipo de recursos humanos la evaluará tan pronto como le sea posible, en caso
                    de que sea escogido, nos pondremos en contacto con usted a través del correo "'.$email.'".');

            }catch (Exception $e){
                return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
            }
        }else{
            return Redirect::back()->withErrors('El email es inválido.');
        }
    }

    function askUs(Request $request){
        $name = htmlspecialchars($request['name']);
        $email = htmlspecialchars($request['email']);
        $question = htmlspecialchars($request['question']);

        if(!$name || !$email || !$question) return abort(404);

        if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
            if(strlen($name) < 1 || strlen($email) < 1 || strlen($question) < 1)  return Redirect::back()->withErrors('Debe completar todos los campos requeridos.');

            try{
                $data = [
                    "email" => $email,
                    "name" => $name,
                    "question" => $question,
                    "subject" => $name.' tiene una pregunta.'
                ];

                Mail::send('emails.FAQ', $data, function($message) use ($data) {
                    $message->to('jacobo@editorialparalelo28.com', 'Jacobo')->subject($data["subject"]);
                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                });

                return Redirect::back()->with('successMessage', 'Su pregunta será respondida tan pronto como nos sea posible,
                    nuestro equipo se pondrá en contacto con usted a través del correo "'.$email.'".');

            }catch (Exception $e){
                return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
            }
        }else{
            return Redirect::back()->withErrors('El email es inválido.');
        }
    }
}
