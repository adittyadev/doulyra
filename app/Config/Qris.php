<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Qris extends BaseConfig
{
    // Teks QRIS statis (hasil scan qris.png).
    // Bisa ditimpa lewat .env dengan: qris.statis = '...'
    public string $statis = '00020101021126570011ID.DANA.WWW011893600915303338570402090333857040303UMI51440014ID.CO.QRIS.WWW0215ID10265528074260303UMI5204599553033605802ID5915Petshop Bigboss6011Kota Padang6105251216304CA1E';
}
