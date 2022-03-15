<?php

namespace Hasura\Metadata;

class LanguagePool
{
    public const STATUS_DONE = 'Done!';

    public const COMMAND_APPLY = 'apply';
    public const COMMAND_APPLY_DESCRIPTION = 'Apply Hasura metadata';
    public const COMMAND_APPLY_PROCESSING = 'Applying...';
    public const OPTION_ALLOW_INCONSISTENT = 'allow-inconsistent';
    public const OPTION_ALLOW_INCONSISTENT_DESCRIPTION = 'Allow inconsistent when apply metadata files.';
    public const OPTION_ALLOW_NO_METADATA = 'allow-no-metadata';
    public const OPTION_ALLOW_NO_METADATA_DESCRIPTION = 'Allow no metadata files.';

    public const COMMAND_CLEAR = 'clear';
    public const COMMAND_CLEAR_DESCRIPTION = 'Clear Hasura metadata';
    public const COMMAND_CLEAR_PROCESSING = 'Clearing...';

    public const COMMAND_GET_INCONSISTENT = 'get-inconsistent';
    public const COMMAND_GET_INCONSISTENT_DESCRIPTION = 'Get inconsistent Hasura metadata';
    public const COMMAND_GET_INCONSISTENT_PROCESSING = 'Getting...';

    public const INFO_NOT_FOUND_METADATA_FILE = 'Not found metadata files.';
    public const INFO_NO_METADATA_APPLY = 'No metadata files to apply.';
    public const INFO_CHECK_SERVER_CONFIG = 'Please check your Hasura server configuration.';
    public const INFO_CURRENT_METADATA_IS_CONSISTENT = 'Current metadata is consistent with database sources!';
}