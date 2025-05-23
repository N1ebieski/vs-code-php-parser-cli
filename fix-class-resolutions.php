<?php

$files = [
    'Application.php',
    'Vite.php',
    'Mix.php',
    'Precognition.php',
    'Configuration/Middleware.php',
    'Configuration/Exceptions.php',
    'Configuration/ApplicationBuilder.php',
    'PackageManifest.php',
    'Bootstrap/RegisterProviders.php',
    'Bootstrap/LoadEnvironmentVariables.php',
    'Bootstrap/LoadConfiguration.php',
    'Bootstrap/BootProviders.php',
    'Bootstrap/SetRequestForConsole.php',
    'Bootstrap/RegisterFacades.php',
    'Bootstrap/HandleExceptions.php',
    'ComposerScripts.php',
    'Auth/Access/Authorizable.php',
    'Auth/Access/AuthorizesRequests.php',
    'Auth/User.php',
    'Auth/EmailVerificationRequest.php',
    'ProviderRepository.php',
    'EnvironmentDetector.php',
    'Providers/FormRequestServiceProvider.php',
    'Providers/ArtisanServiceProvider.php',
    'Providers/ComposerServiceProvider.php',
    'Providers/ConsoleSupportServiceProvider.php',
    'Providers/FoundationServiceProvider.php',
    'Inspiring.php',
    'MaintenanceModeManager.php',
    'Bus/PendingChain.php',
    'Bus/DispatchesJobs.php',
    'Bus/PendingDispatch.php',
    'Bus/Dispatchable.php',
    'Bus/PendingClosureDispatch.php',
    'Exceptions/ReportableHandler.php',
    'Exceptions/Handler.php',
    'Exceptions/Whoops/WhoopsExceptionRenderer.php',
    'Exceptions/Whoops/WhoopsHandler.php',
    'Exceptions/RegisterErrorViewPaths.php',
    'CacheBasedMaintenanceMode.php',
    'Testing/Traits/CanConfigureMigrationCommands.php',
    'Testing/DatabaseTruncation.php',
    'Testing/RefreshDatabaseState.php',
    'Testing/DatabaseMigrations.php',
    'Testing/WithFaker.php',
    'Testing/WithConsoleEvents.php',
    'Testing/WithoutMiddleware.php',
    'Testing/TestCase.php',
    'Testing/RefreshDatabase.php',
    'Testing/LazilyRefreshDatabase.php',
    'Testing/Wormhole.php',
    'Testing/DatabaseTransactions.php',
    'Testing/Concerns/InteractsWithSession.php',
    'Testing/Concerns/InteractsWithContainer.php',
    'Testing/Concerns/InteractsWithDatabase.php',
    'Testing/Concerns/InteractsWithTime.php',
    'Testing/Concerns/MakesHttpRequests.php',
    'Testing/Concerns/InteractsWithViews.php',
    'Testing/Concerns/InteractsWithAuthentication.php',
    'Testing/Concerns/InteractsWithDeprecationHandling.php',
    'Testing/Concerns/InteractsWithRedis.php',
    'Testing/Concerns/InteractsWithExceptionHandling.php',
    'Testing/Concerns/InteractsWithConsole.php',
    'Testing/Concerns/WithoutExceptionHandlingHandler.php',
    'Testing/Concerns/InteractsWithTestCaseLifecycle.php',
    'Testing/DatabaseTransactionsManager.php',
    'Support/Providers/AuthServiceProvider.php',
    'Support/Providers/RouteServiceProvider.php',
    'Support/Providers/EventServiceProvider.php',
    'Http/Middleware/HandlePrecognitiveRequests.php',
    'Http/Middleware/VerifyCsrfToken.php',
    'Http/Middleware/ConvertEmptyStringsToNull.php',
    'Http/Middleware/TrimStrings.php',
    'Http/Middleware/ValidateCsrfToken.php',
    'Http/Middleware/ValidatePostSize.php',
    'Http/Middleware/TransformsRequest.php',
    'Http/Middleware/CheckForMaintenanceMode.php',
    'Http/Middleware/PreventRequestsDuringMaintenance.php',
    'Http/Middleware/Concerns/ExcludesPaths.php',
    'Http/FormRequest.php',
    'Http/MaintenanceModeBypassCookie.php',
    'Http/Events/RequestHandled.php',
    'Http/HtmlDumper.php',
    'Http/Kernel.php',
    'FileBasedMaintenanceMode.php',
    'ViteManifestNotFoundException.php',
    'Routing/PrecognitionCallableDispatcher.php',
    'Routing/PrecognitionControllerDispatcher.php',
    'Events/MaintenanceModeDisabled.php',
    'Events/DiscoverEvents.php',
    'Events/DiagnosingHealth.php',
    'Events/LocaleUpdated.php',
    'Events/PublishingStubs.php',
    'Events/VendorTagPublished.php',
    'Events/Dispatchable.php',
    'Events/MaintenanceModeEnabled.php',
    'Validation/ValidatesRequests.php',
    'Concerns/ResolvesDumpSource.php',
    'Console/LangPublishCommand.php',
    'Console/PackageDiscoverCommand.php',
    'Console/TestMakeCommand.php',
    'Console/ConfigPublishCommand.php',
    'Console/ViewCacheCommand.php',
    'Console/UpCommand.php',
    'Console/EnvironmentCommand.php',
    'Console/DownCommand.php',
    'Console/EventGenerateCommand.php',
    'Console/ProviderMakeCommand.php',
    'Console/ExceptionMakeCommand.php',
    'Console/DocsCommand.php',
    'Console/VendorPublishCommand.php',
    'Console/RuleMakeCommand.php',
    'Console/RouteClearCommand.php',
    'Console/ConfigCacheCommand.php',
    'Console/KeyGenerateCommand.php',
    'Console/EnumMakeCommand.php',
    'Console/ViewMakeCommand.php',
    'Console/ObserverMakeCommand.php',
    'Console/CliDumper.php',
    'Console/EnvironmentEncryptCommand.php',
    'Console/EventClearCommand.php',
    'Console/ComponentMakeCommand.php',
    'Console/StorageLinkCommand.php',
    'Console/EventListCommand.php',
    'Console/InterfaceMakeCommand.php',
    'Console/ChannelMakeCommand.php',
    'Console/ClosureCommand.php',
    'Console/ApiInstallCommand.php',
    'Console/BroadcastingInstallCommand.php',
    'Console/QueuedCommand.php',
    'Console/EventCacheCommand.php',
    'Console/RouteListCommand.php',
    'Console/ResourceMakeCommand.php',
    'Console/ClearCompiledCommand.php',
    'Console/EnvironmentDecryptCommand.php',
    'Console/ConfigShowCommand.php',
    'Console/EventMakeCommand.php',
    'Console/StorageUnlinkCommand.php',
    'Console/ChannelListCommand.php',
    'Console/PolicyMakeCommand.php',
    'Console/StubPublishCommand.php',
    'Console/InteractsWithComposerPackages.php',
    'Console/ScopeMakeCommand.php',
    'Console/ConfigClearCommand.php',
    'Console/RequestMakeCommand.php',
    'Console/JobMakeCommand.php',
    'Console/ConsoleMakeCommand.php',
    'Console/CastMakeCommand.php',
    'Console/ServeCommand.php',
    'Console/RouteCacheCommand.php',
    'Console/OptimizeClearCommand.php',
    'Console/OptimizeCommand.php',
    'Console/ListenerMakeCommand.php',
    'Console/MailMakeCommand.php',
    'Console/ModelMakeCommand.php',
    'Console/ClassMakeCommand.php',
    'Console/ViewClearCommand.php',
    'Console/NotificationMakeCommand.php',
    'Console/TraitMakeCommand.php',
    'Console/AboutCommand.php',
    'Console/Kernel.php',
    'AliasLoader.php',
];

echo "Removing duplicate class resolutions from Laravel Zero...\n\n";

$results = [];

foreach ($files as $file) {
    $path = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/' . $file;

    if (!file_exists($path)) {
        $results[] = 'x';
        continue;
    }

    $results[] = '.';
    unlink($path);
}

foreach (array_chunk($results, 50) as $chunk) {
    echo implode('', $chunk) . "\n";
}

echo "\n\nDone!\n\n";
