<?php
class Constants {
    const DB_HOST='127.0.0.1';
    const DB_USER='hieutct';
    const DB_PASS='123';
    const DB_NAME='extrim';
    const NUMPRODUCT = 10;
    const NUMSERVICE = 10;
    const HOME_URL = 'http://localhost.extrim.com';
    const TOKEN_URL_CALLBACK = 'http://localhost:4200/callback';
    const FB_APP_ID = '1874731906079001';
    const FB_APP_SERECT = '5113c7d97fbe314ef228874903c17bc3';
    const FB_APP_V = 'v2.8';

    const G_CLIENT_ID = '167305294486-bepsvn9j4mdj68s33du5ta58i9t8p52h.apps.googleusercontent.com';
    const G_CLIENT_SECRECT = 'nXF40oS0Gu6EU9S5SGuB628c';

    const DIR_UPLOAD = 'uploads';
    const RESULT = array(
        'error' => true,
        'message' => ''
    );

    const DEFAULT_EMPTY = -1;

    //message
    const MSS_NOT_PERFORMANCE = 'not performance!';
    const MSS_NOT_SUPPORT = 'api is not support!';
    const MSS_MISS_PARAMS = 'missed params!';
    const MSS_API_NOTWORK = 'api is not work!';
    const MSS_CREATED = 'successfully created!';
    const MSS_UPDATED = 'successfully updated!';
    const MSS_DELETE = 'successfully deleted!';
    const MSS_DUPPLICATION_KEY = 'dupplication key!';
}