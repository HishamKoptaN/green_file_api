<?php
function successResponse($data = null, $code = 200)
{
    return response()->json(
        $data,
        $code,
    );
}

function failureResponse(
    $errors = null,
    $status = 500,
) {
    if (is_array($errors)) {
        $errors = implode(
            ', ',
            $errors,
        );
    }
    return response()->json(
        [
            'error' => $errors,
        ],
        $status,
    );
}
