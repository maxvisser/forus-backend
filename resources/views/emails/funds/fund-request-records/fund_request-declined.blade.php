<?php
    /** @var string $fund_name */
    /** @var string $webshop_link Link to webshop */
    /** @var string rejection_note Reason for rejection */
    $viewData = compact('fund_name', 'webshop_link', 'rejection_note');
?>
@extends('emails.base')

@section('title', mail_trans('fund_request_record_declined.title', $viewData))
@section('html')
    {{ mail_trans('dear_citizen', $viewData) }}
    <br/>
    <br/>
    {{ mail_trans('fund_request_record_declined.message', $viewData) }}
    {{ json_encode_pretty($viewData) }}
    <br/>
    <br/>
    {!! mail_trans('fund_request_record_declined.webshop_button', $viewData) !!}
@endsection
