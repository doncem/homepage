<?php
/**
 * Namespace, key and expiry collector.<br />
 * Each element is distincted by key-string attached: 'NAMESPACE_', 'KEY_' and 'EXPIRE_'
 * @package common
 */
class MemcacheNamespaces {
    // namespaces
    const NAMESPACE_JUKEBOX     = "jukebox_data";
    const NAMESPACE_PAGE        = "page_data";
    // keys
    const KEY_JUKEBOX_SEARCH    = "search";
    const KEY_MOVIES_DATA       = "movies";
    const KEY_MOVIES_JAVASCRIPT = "javascript";
    // expiry time
    const EXPIRE_IN_30_DAYS     = 2592000;// = 60 * 60 * 24 * 30
}
