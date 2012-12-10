<?php
/**
 * Namespace, key and expiry collector.<br />
 * Each element is distincted by key-string attached: 'NAMESPACE_', 'KEY_' and 'EXPIRE_'
 */
class MemcacheNamespaces {
    // namespaces
    const NAMESPACE_PAGE        = "page_data";
    // keys
    const KEY_MOVIES_DATA       = "movies";
    const KEY_MOVIES_JAVASCRIPT = "javascript";
    // expiry time
    const EXPIRE_MOVIES         = 2592000;// = 60 * 60 * 24 * 30
}
