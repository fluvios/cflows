u8 QUE15_i2c_i2cReadRegister( u8 dev_id, u8 reg ) {
  u8 bus_id = i2cGetDeviceBusId( dev_id ),
     dev_addr = i2cGetDeviceRegAddr( dev_id ),
     ret = 0xFF;

  for( u32 i = 0; i < 8 && ret == 0xFF; i++ ) {
    if( i2cSelectDevice( bus_id, dev_addr ) && i2cSelectRegister( bus_id, reg ) ) {
      if( i2cSelectDevice( bus_id, dev_addr | 1 ) ) {
        i2cWaitBusy( bus_id );
        i2cStop( bus_id, 1 );
        i2cWaitBusy( bus_id );

        ret = *i2cGetDataReg( bus_id );
      }
    }
    *i2cGetCntReg( bus_id ) = 0xC5;
    i2cWaitBusy( bus_id );
  }

  wait( 3ULL );

  return ret;
}

bool QUE16_i2c_i2cWriteRegister( u8 dev_id, u8 reg, u8 data ) {
  u8 bus_id = i2cGetDeviceBusId( dev_id ),
     dev_addr = i2cGetDeviceRegAddr( dev_id );

  bool ret = false;

  for( u32 i = 0; i < 8 && !ret; i++ ) {
    if( i2cSelectDevice( bus_id, dev_addr ) && i2cSelectRegister( bus_id, reg ) ) {
      i2cWaitBusy( bus_id );
      *i2cGetDataReg( bus_id ) = data;
      *i2cGetCntReg( bus_id ) = 0xC1;
      i2cStop( bus_id, 0 );

      if( i2cGetResult( bus_id ) ) ret = true;
    }
    *i2cGetCntReg( bus_id ) = 0xC5;
    i2cWaitBusy( bus_id );
  }

  wait( 3ULL );

  return ret;
}

int QUE17_crypto_ctrNandRead( u32 sector, u32 sectorCount, u8 * outbuf ) {
  __attribute__( ( aligned( 4 ) ) ) u8 tmpCtr[sizeof( nandCtr )];
  memcpy( tmpCtr, nandCtr, sizeof( nandCtr ) );
  aes_advctr( tmpCtr, ( ( sector + fatStart ) * 0x200 ) / AES_BLOCK_SIZE, AES_INPUT_BE | AES_INPUT_NORMAL );

  //Read
  int result = sdmmc_nand_readsectors( sector + fatStart, sectorCount, outbuf );

  //Decrypt
  aes_use_keyslot( nandSlot );
  aes( outbuf, outbuf, sectorCount * 0x200 / AES_BLOCK_SIZE, tmpCtr, AES_CTR_MODE, AES_INPUT_BE | AES_INPUT_NORMAL );

  return result;
}

static inline void QUE18_utils_startChrono( void ) {
  static bool isChronoStarted = false;

  if( isChronoStarted ) return;

  REG_TIMER_CNT( 0 ) = 0; //67MHz
  for( u32 i = 1; i < 4; i++ ) REG_TIMER_CNT( i ) = 4; //Count-up

  for( u32 i = 0; i < 4; i++ ) REG_TIMER_VAL( i ) = 0;

  REG_TIMER_CNT( 0 ) = 0x80; //67MHz; enabled
  for( u32 i = 1; i < 4; i++ ) REG_TIMER_CNT( i ) = 0x84; //Count-up; enabled

  isChronoStarted = true;
}

bool QUE19_fs_fileWrite( const void * buffer, const char * path, u32 size ) {
  FIL file;
  FRESULT result;

  switch( f_open( &file, path, FA_WRITE | FA_OPEN_ALWAYS ) ) {
  case FR_OK: {
    unsigned int written;
    result = f_write( &file, buffer, size, &written );
    if( result == FR_OK ) result = f_truncate( &file );
    result |= f_close( &file );

    return result == FR_OK && ( u32 )written == size;
  }
  case FR_NO_PATH:
    for( u32 i = 1; path[i] != 0; i++ )
      if( path[i] == '/' ) {
        char folder[i + 1];
        memcpy( folder, path, i );
        folder[i] = 0;
        result = f_mkdir( folder );
      }

    return result == FR_OK && fileWrite( buffer, path, size );
  default:
    return false;
  }
}

void QUE20_sdmmc_get_cid( bool isNand, u32 * info ) {
  struct mmcdevice * device = isNand ? &handleNAND : &handleSD;

  inittarget( device );

  // use cmd7 to put sd card in standby mode
  // CMD7
  sdmmc_send_command( device, 0x10507, 0 );

  // get sd card info
  // use cmd10 to read CID
  sdmmc_send_command( device, 0x1060A, device->initarg << 0x10 );

  for( int i = 0; i < 4; ++i )
    info[i] = device->ret[i];

  // put sd card back to transfer mode
  // CMD7
  sdmmc_send_command( device, 0x10507, device->initarg << 0x10 );
}
