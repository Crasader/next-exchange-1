<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Crypt;
use Image;
use Auth;

class EncryptionController extends Controller
{
    public static function pkcs7_pad($data, $size)
    {
        $length = $size - strlen($data) % $size;
        return $data . str_repeat(chr($length), $length);
    }
    public static function pkcs7_unpad($data)
    {
        return substr($data, 0, -ord($data[strlen($data) - 1]));
    }

    public static function generateKeys() {
        // Create the private and public key
        // To use signature with private and public key

        //$data = 'plaintext data goes here';
        // Encrypt the data to $encrypted using the public key
        //openssl_public_encrypt($data, $encrypted, $pubKey);

        // Decrypt the data using the private key and store the results in $decrypted
        //openssl_private_decrypt($encrypted, $decrypted, $privKey);
        //echo $decrypted;

        $keysize = "1024";
        $res = openssl_pkey_new(array('private_key_bits' => (int)$keysize));

        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privKey);

        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];

        return $privKey.' '.$pubKey;
    }

    public static function generateSalt() {
        return str_random(64);
    }

    public static function generateSecureSalt($salt) {
        $secure_salt = UserController::getUserSalt();
        return $secure_salt.$salt;
    }

    public static function generatePk($passphrase, $mix_salt, $iv) {
        return openssl_encrypt(
            self::pkcs7_pad($passphrase, 16), // padded data
            'AES-256-CBC',        // cipher and mode
            $mix_salt,                        // secret key
            0,                    // options (not used)
            $iv                           // initialisation vector
        );
    }

    public static function regeneratePk($pk, $mix_salt, $iv) {
        return self::pkcs7_unpad(openssl_decrypt(
            $pk,
            'AES-256-CBC',
            $mix_salt,
            0,
            $iv
        ));
    }

    public function secureImageUpload(Request $request, $repository, $encryped = 0)
    {

        // We have this repositories
        //
        // - Suggestions (logo's for suggestions)
        //

        if (!$repository) return false;

        $this->validate($request, [
            'file' => 'mimes:pdf,png,jpeg,jpg|max:2048'
        ], [
            'file.mimes' => 'Image must be of type .pdf, .png, .jpeg, .jpg',
            'file.max' => 'Image can be maximum 2 MB'
        ]);

        if ($request->file('file') && $request->file('file')->isValid()) {

            $image = $request->file('file');
            $extension = $image->getClientOriginalExtension(); // getting image extension;
            $filename = md5(Auth::id() . '_' . date("YmdHis") . rand(1111, 9999)) . '.' . $extension;

            // Encrypt the file
            if ($encryped == 0) {
                Storage::disk('local')->put($repository . '/' . $filename, file_get_contents($image->getRealPath()));
            } else {
                $filename = $filename . '.aes';
                Storage::disk('local')->put($repository . '/' . $filename, Crypt::encrypt(file_get_contents($image->getRealPath())));
            }

            // If storage is succesfull
            if (Storage::exists($repository . '/' . $filename)) {
                $request->session()->flash('success_msg', 'Image Update Successfully.');
                return $filename;

            } else {
                $request->session()->flash('error_msg', 'Image Update not successfully.');
                return false;
            }
        }
        return null;
    }

    public static function secureImageDownload($filename, $repository)
    {

        /*
         * Needs to be included in <img src='{here}'>
         */

        if (is_null($filename)) return null;

        if (!$repository) return null;

        if (@!Storage::exists($repository . '/' . $filename)) return null;

        if (mb_substr($filename, -3) == 'aes') {
            $file = Crypt::decrypt(Storage::get($repository . '/' . $filename));
        } else {
            $file = Storage::get($repository . '/' . $filename);
        }

        return Image::make($file)->encode('data-url');
    }




}
