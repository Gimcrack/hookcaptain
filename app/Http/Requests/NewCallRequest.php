<?php

namespace App\Http\Requests;

use App\Hook;
use Illuminate\Foundation\Http\FormRequest;

class NewCallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }


    public function hasRecipient(  )
    {
        return filter_var($this['recipient'],FILTER_VALIDATE_EMAIL);
    }


    public function hasSubject(  )
    {
        return isset($this['subject']);
    }


    public function hasBody(  )
    {
        return isset($this['body-plain']);
    }


    public function findHookByRecipient( $recipient )
    {
        if ( ! $this->hasRecipient() ) return false;
        
        $slug = str_before($recipient,'@');

        return Hook::findBySlug($slug);
    }


    public function findHookBySubject( $subject )
    {
        if ( ! $this->hasSubject() ) return false;

        $matches = [];
        preg_match("/\[(\S{16})\]/",$subject,$matches);

        if ( ! isset($matches[1]) ) return false;

        return Hook::findBySlug($matches[1]);
    }


    public function findHookByBody( $body )
    {
        if ( ! $this->hasBody() ) return false;

        $matches = [];
        preg_match("/\[(\S{16})\]/",$body,$matches);

        if ( ! isset($matches[1]) ) return false;

        return Hook::findBySlug($matches[1]);
    }


    /**
     * Get the hook for the call.
     *   If it doesn't exist, create one with default values
     * @return Hook
     */
    public function getHook()
    {
        if ( $hook = $this->findHookByRecipient($this['recipient']) )
            return $hook;

        if ( $hook = $this->findHookBySubject($this['subject']) )
            return $hook;

        if ( $hook = $this->findHookByBody($this['body-plain']) )
            return $hook;

        return Hook::defaults();
    }
}
