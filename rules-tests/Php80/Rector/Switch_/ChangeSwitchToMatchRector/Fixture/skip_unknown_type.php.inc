<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipUnknownType
{
    public function codeToMessage($code): string
    {
        switch ($code) {
            case \UPLOAD_ERR_INI_SIZE:
                $message = 'Die Dateigr&ouml;&szlig;e der gew&auml;hlten Datei &uuml;berschreitet die upload_max_filesize Einstellung der php.ini';
                break;
            case \UPLOAD_ERR_FORM_SIZE:
                $message = 'Die Dateigr&ouml;&szlig;e der gew&auml;hlten Datei &uuml;berschreitet die laut Formular erlaubte MAX_FILE_SIZE';
                break;
            case \UPLOAD_ERR_PARTIAL:
                $message = 'Die Datei wurde nur teilweise hochgeladen';
                break;
            case \UPLOAD_ERR_NO_FILE:
                $message = 'Es wurde keine Datei ausgew&auml;hlt';
                break;
            case \UPLOAD_ERR_NO_TMP_DIR:
                $message = 'Tempor&auml;res Verzeichnis fehlt';
                break;
            case \UPLOAD_ERR_CANT_WRITE:
                $message = 'Datei konnte nicht zwischengespeichert werden';
                break;
            case \UPLOAD_ERR_EXTENSION:
                $message = 'Datei-Upload durch Plugin gestoppt';
                break;

            default:
                $message = 'Unbekannter Fehler';
                break;
        }

        return $message;
    }
}
