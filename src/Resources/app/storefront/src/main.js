import MyfavMigWorkerPlugin from './myfav-mig-worker-plugin/myfav-mig-worker-plugin.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('MyfavMigWorkerPlugin', MyfavMigWorkerPlugin, '[myfav-mig-worker]');