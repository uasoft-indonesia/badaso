<?php

namespace Uasoft\Badaso\Helpers;

class CheckBase64
{
    protected $is_valid = true;
    protected $message = '';
    protected $mimetype_list = [
        'application/epub+zip',
        'application/gzip',
        'application/java-archive',
        'application/json',
        'application/ld+json',
        'application/msword',
        'application/octet-stream',
        'application/ogg',
        'application/pdf',
        'application/rtf',
        'application/vnd.amazon.ebook',
        'application/vnd.apple.installer+xml',
        'application/vnd.mozilla.xul+xml',
        'application/vnd.ms-excel',
        'application/vnd.ms-fontobject',
        'application/vnd.ms-powerpoint',
        'application/vnd.oasis.opendocument.presentation',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.rar',
        'application/vnd.visio',
        'application/x-7z-compressed',
        'application/x-abiword',
        'application/x-bzip',
        'application/x-bzip2',
        'application/x-csh',
        'application/x-freearc',
        'application/x-httpd-php',
        'application/x-sh',
        'application/x-shockwave-flash',
        'application/x-tar',
        'application/xhtml+xml',
        'application/xml',
        'application/zip',
        'audio/3gpp',
        'audio/3gpp2',
        'audio/aac',
        'audio/midi',
        'audio/mpeg',
        'audio/ogg',
        'audio/opus',
        'audio/wav',
        'audio/webm',
        'audio/x-midi',
        'font/otf',
        'font/ttf',
        'font/woff',
        'font/woff2',
        'image/bmp',
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'image/tiff',
        'image/vnd.microsoft.icon',
        'image/webp',
        'text/calendar',
        'text/css',
        'text/csv',
        'text/html',
        'text/javascript',
        'text/javascript',
        'text/plain',
        'text/xml',
        'video/3gpp',
        'video/3gpp2',
        'video/mp2t',
        'video/mpeg',
        'video/mp4',
        'video/ogg',
        'video/webm',
        'video/x-msvideo',
    ];

    public function __construct($param)
    {
        $params = explode(';', $param);
        if (count($params) != 2) {
            $this->is_valid = false;
            $this->message = __('badaso.validation.base64.length_invalid');

            return;
        }

        $header = $params[0];
        $body = $params[1];

        $headers = explode(':', $header);

        if (count($headers) != 2) {
            $this->is_valid = false;
            $this->message = __('badaso.validation.base64.header_invalid');

            return;
        }

        $mimetype = $headers[1];
        if (! in_array($mimetype, $this->mimetype_list)) {
            $this->is_valid = false;
            $this->message = __('badaso.validation.base64.mimetype_invalid');

            return;
        }
    }

    public function isValid()
    {
        return $this->is_valid;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
