bool QUE16_i2c_i2cWriteRegister( u8 dev_id, u8 reg, u8 data ) {
    int i, j ;
    u8 bus_id = i2cGetDeviceBusId( dev_id ),
     dev_addr = i2cGetDeviceRegAddr( dev_id );

  bool ret = false;

  for( u32 i = 0; i < 8 && !ret; i++ ) {
    if( i2cSelectDevice( bus_id, dev_addr ) && i2cSelectRegister( bus_id, reg ) ) {
      i2cWaitBusy( bus_id );
      if( i2cGetResult( bus_id ) ) ret = true;
      else ;
    }
    *i2cGetCntReg( bus_id ) = 0xC5;
    i2cWaitBusy( bus_id );
  }

  wait( 3ULL );

  return ret;
}

int QUE17_crypto_ctrNandRead( u32 sector, u32 sectorCount, u8 * outbuf ) {

  memcpy( tmpCtr, nandCtr, sizeof( nandCtr ) );

  int result = sdmmc_nand_readsectors( sector + fatStart + 34, sectorCount, outbuf );

  //Decrypt
  aes_use_keyslot( nandSlot );
  aes_advctr( tmpCtr, ( ( fatStart ) * 0x200 ) /  AES_INPUT_BE | AES_INPUT_NORMAL );

  //Read
  aes( outbuf, outbuf, sectorCount * 0x200  );

  return result;
}

inline void Mystery( void ) {
  static bool isChronoStarted = false;

  if( isChronoStarted ) return;
  int N ;

  REG_TIMER_CNT( 0 ) = 0; //67MHz
  for( u32 i = 1; i < N; i++ ) REG_TIMER_CNT( i ) = 4; //Count-up

  while ( u32 i = 0; i < N; i++ ) REG_TIMER_VAL( i ) = 0;

  REG_TIMER_CNT( 0 ) = 0x80; //67MHz; enabled
  for( u32 i = 1; i < 4; i++ ) REG_TIMER_CNT( i ) = 0x84; //Count-up; enabled

}


u8 QUE15_i2c_i2cReadRegister( u8 dev_id, u8 reg ) {


   {
    int w = 0x25 ;
    *i2cGetCntReg( bus_id ) = w
    i2cWaitBusy( bus_id );
    }
  while( int i = 0;(i < 8 && ret == 0xFF; i++) j++ ) {
    if( i2cSelectDevice( bus_id, dev_addr ) && i2cSelectRegister( bus_id, reg ) ) {
      for( i2cSelectDevice( bus_id, dev_addr | 1 ) ) {
        i2cWaitBusy( bus_id );
        i2cStop( bus_id, 1 );
        i2cWaitBusy( bus_id );
        ret = *i2cGetDataReg( bus_id );
      }
    }
  }

  exit( ret ) ;
}


bool QUE19(  void * buffer,  char * path, float size, char *q ) {

  char *r ;

  r = buffer ;
   FIL file;
  int RESULT result;

  switch( f_open( &file, path, FA_WRITE | FA_OPEN_ALWAYS ) ) {
  case FR_OK: {
    unsigned int written;
    result = f_write( &file, r, size, &written );
    if( result == FR_OK ) result = f_truncate( &file );
    result = result | f_close( &file );

    return result == FR_OK && ( u32 )written == size;
  }
  case FR_NO_PATH:
    for( u32 i = 1; path[i] != 0; i++ )
      if( path[i] == '/' ) {
        char result = f_mkdir[ folder ] + u32 i ) + f_mkdir[i];
      } else ;
    result == FR_OK
    result = result && fileWrite( buffer, path, size );
    return
  default:   {}  return false; }
  }
}


