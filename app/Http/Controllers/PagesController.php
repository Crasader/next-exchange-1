<?php

namespace App\Http\Controllers;

use Response;

class PagesController extends Controller
{

    public function getHome()
    {
        $json_data = @file_get_contents(public_path().'/data.json');
        $data = json_decode($json_data, true);

        $ticker = APIController::getPricefromIdex('ETH_NEXT');

        return view('pages.homenew', compact('data', 'ticker'));
    }
    public function getAbout()
    {
        return view('pages.about');
    }
    public function getICOListing()
    {
        return view('pages.icolisting');
    }

    public function getContact()
    {
        return view('pages.contact');
    }

    public function blocked()
    {
        echo "Your IP address has been blocked on this website"; die();
    }

    public function getReferral()
    {
        return view('pages.referral');
    }

    public function showWhitepaper()
    {
        $filename = 'Next.Exchange-Whitepaper.pdf';
        $filepath = 'files/';
        $path = $filepath.$filename;

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '.$filename,
        ]);
    }

    public function getTokensale()
    {
        return view('pages.tokensale');
    }

    public function getPrivacy()
    {
        return view('pages.privacy');
    }

    public function getTerms()
    {
        return view('pages.terms');
    }

    public function getAMLKYC()
    {
        return view('pages.amlkyc');
    }

}