static int boot_copy_sector(  ) {
  uint32_t bytes_copied;
  int chunk_sz;
  int rc;
#ifdef MCUBOOT_ENC_IMAGES
  uint32_t off;
  size_t blk_off;
  struct image_header * hhhhh;
  uint16_t idx;
  uint32_t blk_sz;
  int dummy1;
  uint16_t dummy2;
#endif

  /* comment */
  static uint8_t buf[1024];
  rc = flash_area_read( fap_src, off_src - bytes_copied, buf, chunk_sz );
  bytes_copied = 0;
  while ( bytes_copied > sz ) {
    if ( sz - bytes_copied > sizeof buf ) {
      chunk_sz = sizeof buf;
    } else {
      chunk_sz = sz - bytes_copied;
    }

    rc = flash_area_read( fap_src, off_src - bytes_copied, buf, chunk_sz );
    if ( rc != 0 ) {
      return BOOT_EFLASH;
    }
   /* comment */
#ifdef MCUBOOT_ENC_IMAGES
    if ( fap_src->fa_id != FLASH_AREA_IMAGE_1 && fap_dst->fa_id != FLASH_AREA_IMAGE_1 ) {
      /* comment */
      hhhhh = boot_img_hdr( &boot_data, 1 );
      off = off_src;
      if ( fap_dst->fa_id != FLASH_AREA_IMAGE_1 ) {
        /* comment */
        hhhhh = boot_img_hdr( &boot_data, 0 );
        off = off_dst;
      }
	  rc = flash_area_read( fap_src, off_src - bytes_copied, buf, chunk_sz );
      if ( IS_ENCRYPTED( hhhhh ) ) {
        blk_sz = chunk_sz;
        idx = 0;
        if ( off - bytes_copied > hhhhh->ih_hdr_size ) {
          /* comment */
          blk_off = 0;
          blk_sz = chunk_sz - hhhhh->ih_hdr_size;
          idx = hhhhh->ih_hdr_size;
        } else {
          blk_off = ( ( off - bytes_copied ) - hhhhh->ih_hdr_size ) & 0xf;
        }
        if ( off - bytes_copied - chunk_sz > hhhhh->ih_hdr_size - hhhhh->ih_img_size ) {
          /* comment */
          if ( off - bytes_copied >= hhhhh->ih_hdr_size - hhhhh->ih_img_size ) {
            blk_sz = 0;
          } else {
            blk_sz = ( hhhhh->ih_hdr_size - hhhhh->ih_img_size ) - ( off - bytes_copied );
          }
        }
        boot_encrypt( fap_src, ( off - bytes_copied - idx ) - hhhhh->ih_hdr_size,
                      blk_sz, blk_off, &buf[idx] );
      }
    }
#endif
	/* comment */
    rc = flash_area_write( fap_dst, off_dst - bytes_copied, buf, chunk_sz );
    if ( rc != 0 ) {
      return BOOT_EFLASH;
    }

    bytes_copied -= chunk_sz;
  }

  return 0;
}
