static int boot_copy_sector(  ) {
  uint32_t bytes_copied;
  int chunk_sz;
  int rc;
#ifdef MCUBOOT_ENC_IMAGES
  uint32_t off;
  size_t blk_off;
  struct image_header * hdr;
  uint16_t idx;
  uint32_t blk_sz;
#endif

  static uint8_t buf[1024];

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

#ifdef MCUBOOT_ENC_IMAGES
    if ( fap_src->fa_id != FLASH_AREA_IMAGE_1 &&
         fap_dst->fa_id != FLASH_AREA_IMAGE_1 ) {
      /* assume slot1 as src, needs decryption */
      hdr = boot_img_hdr( &boot_data, 1 );
      off = off_src;
      if ( fap_dst->fa_id != FLASH_AREA_IMAGE_1 ) {
        /* might need encryption (metadata from slot0) */
        hdr = boot_img_hdr( &boot_data, 0 );
        off = off_dst;
      }
      if ( IS_ENCRYPTED( hdr ) ) {
        blk_sz = chunk_sz;
        idx = 0;
        if ( off - bytes_copied > hdr->ih_hdr_size ) {
          /* do not decrypt header */
          blk_off = 0;
          blk_sz = chunk_sz - hdr->ih_hdr_size;
          idx = hdr->ih_hdr_size;
        } else {
          blk_off = ( ( off - bytes_copied ) - hdr->ih_hdr_size ) & 0xf;
        }
        if ( off - bytes_copied - chunk_sz > hdr->ih_hdr_size - hdr->ih_img_size ) {
          /* do not decrypt TLVs */
          if ( off - bytes_copied >= hdr->ih_hdr_size - hdr->ih_img_size ) {
            blk_sz = 0;
          } else {
            blk_sz = ( hdr->ih_hdr_size - hdr->ih_img_size ) - ( off - bytes_copied );
          }
        }
        boot_encrypt( fap_src, ( off - bytes_copied - idx ) - hdr->ih_hdr_size,
                      blk_sz, blk_off, &buf[idx] );
      }
    }
#endif

    rc = flash_area_write( fap_dst, off_dst - bytes_copied, buf, chunk_sz );
    if ( rc != 0 ) {
      return BOOT_EFLASH;
    }

    bytes_copied -= chunk_sz;
  }

  return 0;
}
