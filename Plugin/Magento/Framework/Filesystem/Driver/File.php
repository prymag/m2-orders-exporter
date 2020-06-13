<?php

namespace Prymag\OrdersExporter\Plugin\Magento\Framework\Filesystem\Driver;

class File {

    /**
     * Writes one CSV row to the file.
     *
     * @param resource $resource
     * @param array $data
     * @param string $delimiter
     * @param string $enclosure
     * @return int
     * @throws FileSystemException
     */
    public function aroundFilePutCsv(
        \Magento\Framework\Filesystem\Driver\File $subject, 
        callable $proceed,
        $resource, 
        array $data, 
        $delimiter = ',', 
        $enclosure = '"'
    ) {
        /**
         * Security enhancement for CSV data processing by Excel-like applications.
         * @see https://bugzilla.mozilla.org/show_bug.cgi?id=1054702
         *
         * @var $value string|\Magento\Framework\Phrase
         */
        foreach ($data as $key => $value) {
            if (!is_string($value)) {
                $value = (string)$value;
            }
            if (isset($value[0]) && in_array($value[0], ['=', '+', '-'])) {
                $data[$key] = ' ' . $value;
            }
        }

        $result = @fputcsv($resource, $data, $delimiter, $enclosure);
        
        // CONVERT LF TO CRLF
        fseek($resource, -1, SEEK_CUR);
        fwrite($resource, "\r\n");

        if (!$result) {
            throw new FileSystemException(
                new \Magento\Framework\Phrase(
                    'An error occurred during "%1" filePutCsv execution.',
                    [$this->getWarningMessage()]
                )
            );
        }
        return $result;
    }

}