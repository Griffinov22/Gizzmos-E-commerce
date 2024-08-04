<?php

function EchoImage($pathToImage) {
    try {
        $mimeType = mime_content_type($pathToImage);
        $imageData = file_get_contents($pathToImage);
        $base64 = base64_encode($imageData);

        return "data:{$mimeType};base64,{$base64}";

    } catch (Exception $e) {
        return 'NotFound';
    }
} 