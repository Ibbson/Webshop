<?php

class Logger {
    private string $infoFile;
    private string $errorFile;

    public function __construct(string $infoFile = 'info.log', string $errorFile = 'error.log') {
        $this->infoFile = $infoFile;
        $this->errorFile = $errorFile;
    }

    private function writeToFile(string $filePath, string $message): void {
        file_put_contents($filePath, $message . PHP_EOL, FILE_APPEND);
    }

    private function formatMessage(string $level, string $message): string {
        $timestamp = date('Y-m-d H:i:s');
        return "[$timestamp] [$level] $message";
    }

    public function info(string $message): void {
        $formattedMessage = $this->formatMessage('INFO', $message);
        $this->writeToFile($this->infoFile, $formattedMessage);
    }

    public function error(string $message): void {
        $formattedMessage = $this->formatMessage('ERROR', $message);
        $this->writeToFile($this->errorFile, $formattedMessage);
    }
}