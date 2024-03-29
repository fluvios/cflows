/*
 * Copyright (c) 2018 Nordic Semiconductor ASA
 * Copyright (c) 2015 Runtime Inc
 *
 * SPDX-License-Identifier: Apache-2.0
 */

#ifndef __FLASH_MAP_BACKEND_H__
#define __FLASH_MAP_BACKEND_H__

#include <flash_map.h> // the zephyr flash_map

#ifdef __cplusplus
extern "C" {
#endif

/**
 *
 * Provides abstraction of flash regions for type of use.
 * I.e. dude where's my image?
 *
 * System will contain a map which contains flash areas. Every
 * region will contain flash identifier, offset within flash and length.
 *
 * 1. This system map could be in a file within filesystem (Initializer
 * must know/figure out where the filesystem is at).
 * 2. Map could be at fixed location for project (compiled to code)
 * 3. Map could be at specific place in flash (put in place at mfg time).
 *
 * Note that the map you use must be valid for BSP it's for,
 * match the linker scripts when platform executes from flash,
 * and match the target offset specified in download script.
 */
#include <inttypes.h>
#include <sys/types.h>

/* Retrieve the flash device with the given name.
 *
 * Returns the flash device on success, or NULL on failure.
 */
struct device *flash_device_get_binding( char *dev_name );

/*
 * Retrieve a memory-mapped flash device's base address.
 *
 * On success, the address will be stored in the value pointed to by
 * ret.
 *
 * Returns 0 on success, or an error code on failure.
 */
int flash_device_base( uint8_t fd_id, uintptr_t *ret );

int flash_area_id_from_image_slot( int slot );

/* Retrieve the flash sector a given offset belongs to.
 *
 * Returns 0 on success, or an error code on failure.
 */
int flash_area_sector_from_off( off_t off, struct flash_sector *sector );

/*
 * Returns the value expected to be read when accesing any erased
 * flash byte.
 */
uint8_t flash_area_erased_val( const struct flash_area *fap );

/*
 * Reads len bytes from off, and checks if the read data is erased.
 *
 * Returns 1 if erased, 0 if non-erased, and -1 on failure.
 */
int flash_area_read_is_empty( const struct flash_area *fa, uint32_t off,
                              void *dst, uint32_t len );

#ifdef __cplusplus
}
#endif

#endif /* __FLASH_MAP_BACKEND_H__ */
