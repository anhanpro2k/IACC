<?php 
class Account {

    private $id;
    private static $accountObject;
    private static $metaType;
    private static $currentMeta;

    public function __construct()
    {
        if ( is_user_logged_in() ) {
            $this->id = get_current_user_id();
            self::$accountObject = get_userdata( $this->id );
        }
    }

    public static function userMeta( $metaName = '' )
    {
        if ( ! empty ( $metaName ) ) {
            self::$currentMeta = esc_attr( $metaName );
            self::$metaType    = 'user_meta';
        }
        return new self;
    }

    public static function userCustomField( $metaName = '' )
    {
        if ( ! empty ( $metaName ) ) {
            self::$currentMeta = esc_attr( $metaName );
            self::$metaType    = 'custom_field';
        }
        return new self;
    }

    public function get()
    {
        $currentMeta = self::$currentMeta;
        $metaType    = self::$metaType;
        switch ( $metaType ) {
            case 'user_meta';
                global $Supports;
                $supportAccount = isset ( $Supports['account'] ) ? $Supports['account'] : '';
                if ( ! empty ( $supportAccount ) ) {
                    $accountFields = isset ( $supportAccount['fields'] ) ? $supportAccount['fields'] : [];
                    if ( ! empty ( $accountFields ) && in_array ( self::$currentMeta, $accountFields ) ) {
                        return self::$accountObject->$currentMeta;
                    } else {
                        return [
                            'message' => __( 'Trường thông tin không tồn tại!', 'monamedia' ),
                            'status'  => '404'
                        ];
                    }
                }
                break;
            case 'custom_field';
                break;
        }
    }

    public function display()
    {
        $currentMeta = self::$currentMeta;
        $metaType    = self::$metaType;
        switch ( $metaType ) {
            case 'user_meta';
                global $Supports;
                $supportAccount = isset ( $Supports['account'] ) ? $Supports['account'] : '';
                if ( ! empty ( $supportAccount ) ) {
                    $accountFields = isset ( $supportAccount['fields'] ) ? $supportAccount['fields'] : [];
                    if ( ! empty ( $accountFields ) && in_array ( self::$currentMeta, $accountFields ) ) {
                        echo self::$accountObject->$currentMeta;
                    } else {
                        return [
                            'message' => __( 'Trường thông tin không tồn tại!', 'monamedia' ),
                            'status'  => '404'
                        ];
                    }
                }
                break;
            case 'custom_field';
                break;
        }
    }

}