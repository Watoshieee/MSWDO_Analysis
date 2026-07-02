<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ljcKkLijyBOuOrXt',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FD8fDuiFupfNOnRz',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/analysis' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'analysis.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/analysis/demographic' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'analysis.demographic',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/analysis/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'analysis.programs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chatbot/message' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chatbot.reply',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::K6Bac5OhP931B1t4',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JUXHeI2UE2o5VYdE',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/verify-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'otp.verify.form',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'otp.verify',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/resend-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'otp.resend',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/change-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.change',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.change.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/check-username' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.check-username',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/session/ping' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'session.ping',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.programs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'user.profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/announcements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.announcements',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/my-requirements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.my-requirements',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/apply/AICS/aics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-category',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/apply/AICS/medical' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-medical',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/apply/AICS/burial' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-burial',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/pwd-application' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.pwd-application',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/pwd-fillable-form' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.pwd-form',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'user.pwd-form.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/solo-parent-application' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.solo-parent-application',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/pwd-upload-requirement' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.pwd-upload-requirement',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/aics-medical-upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-medical-upload',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/aics-burial-upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-burial-upload',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/aics-medical-upload-batch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-medical-upload-batch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/aics-burial-upload-batch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.aics-burial-upload-batch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/mark-notifications-viewed' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.mark-notifications-viewed',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/appointments/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.appointments.slots',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.appointments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chat/admins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.admins',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chat/send' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chat/unread-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.unread',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/apply/solo-parent' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'solo-parent.apply',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'applications.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users.create',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/municipalities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/municipalities/summary/save' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.summary.save',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/municipalities/summary/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.summary.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/municipalities/monthly/save' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.monthly.save',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/municipalities/monthly/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.monthly.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/barangays' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/barangays/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.bulk-delete',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/barangays/bulk-store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.bulk-store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/barangays/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/barangays/archived/delete-all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.archived.delete-all',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/barangays/bulk-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.bulk-update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/programs/sync-barangays' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.sync-barangays',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/programs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/data/programs/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/municipalities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/municipalities/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/municipalities/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/superadmin/api/laguna-municipalities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/detailed-analysis' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.detailed-analysis',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/requirements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.requirements',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.search',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/appointments/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.slots',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/appointments/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/chat/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.chat.users',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/chat/send' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.chat.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/chat/unread-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.chat.unread',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings/get' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.get',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings/reset' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.reset',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/municipality' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.municipality',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/municipality/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.municipality.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays/bulk-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.bulk-update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays/bulk-store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.bulk-store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays/find-or-create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.find-or-create',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.programs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/programs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.programs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/yearly' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.yearly',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/yearly/save' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.yearly.save',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/csv/import' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.import',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/csv/export' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.export',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/csv/import-log/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.import-log.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/announcements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/mark-notifications-viewed' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.mark-notifications-viewed',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/vision-mission/save' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vision-mission.save',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/yearly' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.yearly.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/yearly/compare' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.yearly.compare',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/barangay-analysis' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'barangay-analysis.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/barangay-analysis/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'barangay-analysis.programs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/barangay-analysis/applicants' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'barangay-analysis.applicants',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/fix-database' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Zi3bpHUDR658v2Om',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.archived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.bulk-delete',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data/barangays/archived/delete-all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.archived.delete-all',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/municipalities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::D0D3jXgTasD6hmck',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::O3Dis0Smizi3HTpU',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jgI7fuGMWVouZpUC',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/verify-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ejJrJ4POM9y8Hs1R',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/resend-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yYi8TwRcvqWMyvKX',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::z88mlgEnkGUQXUxK',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AemqP1APfY3q9vlY',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/chatbot/message' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AKSPdQyrHefwJCxi',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WCMyQeDvaKBba7gU',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZVfr1oD53P0Vt9An',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/change-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aVL5arvMHr6ImlGu',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::D8SWB9ZwqbN8kkk8',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/announcements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::67ejE419FtUW5axy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mAYRClxSBYN3NNh7',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/notifications/read' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ChYOF6XbOrAcQb8a',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6iBRN9Y0qdepyxyK',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Vw6MJrJjZnZ90CXq',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uXNj0Qgloat5kuiH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/solo-parent/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q69lZ4gnrTsVVk0P',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/solo-parent/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uib1k9YVPgIiS8th',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::W4dTsxRBblE2nsIY',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/solo-parent/application' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZnN9v6VA6tyoeqgf',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/solo-parent/requirements/upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yBX6hMpWYA8TPN5j',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/solo-parent/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::68uLe06jqrY56bp3',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/aics/medical/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::w0qH5LAb1UUd8Ypp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/aics/medical/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3qs8aG5BovK1CupJ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/aics/medical/appointment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2RxEXD84fKxm9UKo',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/aics/burial/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KMso42umnZOE0Uaq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/aics/burial/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gu5bXVUszEPxtQMs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/aics/burial/appointment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zOLkD4erIhPQoOtS',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/pwd/application' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PKsxdUCIM3myNt4m',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/pwd/requirements/upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cVK2jUPpsJa9ZxAQ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/device-token' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::H365PdxPwVGOS1LW',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vZ0dhGwiMeeqRrHt',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/chat/admins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tjsYRh0VRvg4FHHT',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/chat/send' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0K73A4gYgZh24Phj',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/v1/chat/unread-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EaxR7fIQpNfSUtbp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/municipalities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::67FSxhvHEaDWHdSJ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xGefmKTJShzvztFz',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NjbKOb53McjQLdif',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/verify-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::C9TVhbv19JQ0wDvv',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/resend-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::c4XBScjCjGMQVRLH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::E9yecjYHTou7GlDu',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7LSbr6GvsL2A8jeq',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/chatbot/message' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Nr1WsyZULsnafXzc',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::y5FqaFUTbh4KD7Ss',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fuziMjbnocTWEVdW',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/change-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mT7mPDZywhDDl7Hz',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rGd3eH9q7zQd5FYw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/announcements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4qJgJjzoJBQmeWcm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KqoTkfTedgx7jkX6',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/notifications/read' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yF8syUeLI59UX8Ng',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::56X5ZAORTEpBegbw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RKf39wfB6RkncmoI',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FOFJLvpSqs3PBo1z',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/solo-parent/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qK2eZHIzQlXB0bdA',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/solo-parent/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VGcyZmR9lX8CODRw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3TUvqwcMj2gBwZq4',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/solo-parent/application' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QHqOcfXQPbQaPzHv',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/solo-parent/requirements/upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yE9UN3EGnh7jaxi3',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/solo-parent/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CrKWn5JEXFW3eTyK',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/aics/medical/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LY3IX7VJyAoEgBeV',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/aics/medical/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LPes6Yj5B8nuYDtW',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/aics/medical/appointment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mUmrbYn1tHlD8kRc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/aics/burial/slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NGd6GgWyVRT44yzG',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/aics/burial/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jT7layhW0QCOmh3q',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/aics/burial/appointment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vuVKC4Jnb3vYyLP2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/pwd/application' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JBZwvuYmqA3KHCiX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/pwd/requirements/upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::066QShTtsHqd5y8E',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/device-token' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0uMdCIfbYvHRmv1h',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1spvGQhWHOXZx3t1',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/chat/admins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iO3xXL6nKPDQzT8X',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/chat/send' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::f7JLvkUQfmobm7Zr',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mobile-api/chat/unread-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gDy50pKsSq0xKV5u',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/a(?|nalysis/municipality/([^/]++)(*:41)|p(?|plications/(?|([^/]++)(?|(*:77)|/requirement(?|s(*:100)|/(?|upload(*:118)|delete(*:132))))|upload\\-batch(*:156))|i/barangays/([^/]++)/([^/]++)(*:194))|dmin/(?|a(?|pp(?|lications/([^/]++)/(?|status(*:248)|archive(*:263)|restore(*:278)|force\\-delete(*:299)|direct\\-delete(*:321)|validate\\-(?|pwd(*:345)|aics(*:357))|mark\\-(?|id\\-ready(*:384)|claimed(*:399)|released(*:415)))|ointments/([^/]++)/(?|c(?|onfirm(*:457)|ancellation/(?|approve(*:487)|reject(*:501)))|validate(*:519)|re(?|ject(*:536)|s(?|chedule/(?|approve(*:566)|reject(*:580))|tore(*:593)))|a(?|dmin\\-reschedule(*:623)|rchive(*:637))|force\\-delete(*:659)))|nnouncements/([^/]++)(?|/(?|deactivate(*:707)|activate(*:723))|(*:732)))|barangay/([^/]++)(*:759)|requirements/([^/]++)(?|(*:791)|/status(*:806))|files/([^/]++)/serve(*:835)|d(?|ebug/storage/([^/]++)(*:868)|ata/(?|barangays/([^/]++)/(?|update(*:911)|archive(*:926)|restore(*:941)|force\\-delete(*:962))|programs/([^/]++)/(?|update(*:998)|delete(*:1012))|yearly/([^/]++)/(?|delete(*:1047)|archive(*:1063)|restore(*:1079)|force\\-delete(*:1101))))|c(?|hat/messages/([^/]++)(*:1138)|sv/(?|template/([^/]++)(*:1170)|import\\-log/([^/]++)(?|(*:1202)|/(?|archive(*:1222)|restore(*:1238)|force\\-delete(*:1260)))))|yearly/view/([^/]++)(*:1293)))|/reset\\-password/([^/]++)(*:1329)|/user/(?|resubmit\\-requirement/([^/]++)(?|(*:1380))|app(?|ly/([^/]++)(*:1407)|ointments/([^/]++)/(?|cancel(*:1444)|reschedule(*:1463))))|/chat/messages/([^/]++)(*:1498)|/s(?|uperadmin/(?|users/(?|([^/]++)(*:1542)|archived\\-json(*:1565)|([^/]++)/(?|archive(*:1593)|restore(*:1609)|force\\-delete(*:1631)))|data/(?|municipalities/(?|summary/([^/]++)(?|/(?|archive(*:1698)|restore(*:1714)|force\\-delete(*:1736))|(*:1746))|monthly/([^/]++)/(?|edit(*:1780)|archive(*:1796)|restore(*:1812)|force\\-delete(*:1834))|([^/]++)(*:1852))|barangays/([^/]++)(?|/(?|edit(*:1891)|archive(*:1907)|restore(*:1923)|force\\-delete(*:1945))|(*:1955))|programs/([^/]++)(?|/(?|archive(*:1996)|restore(*:2012)|force\\-delete(*:2034))|(*:2044)))|municipalities/([^/]++)(?|/(?|restore(*:2092)|force\\-delete(*:2114)|edit(*:2127)|barangays(?|(*:2148)))|(*:2159))|api/barangays/([^/]++)(*:2191))|torage/(.*)(?|(*:2215)))|/barangay\\-analysis/([^/]++)(*:2254)|/mobile\\-api/(?|v1/(?|a(?|pplications/([^/]++)(?|(*:2312))|ics/(?|medical/appointments/([^/]++)(*:2358)|burial/appointments/([^/]++)(*:2395)))|files/([^/]++)/reupload(*:2429)|solo\\-parent/(?|appointments/([^/]++)(?|(*:2478)|/(?|cancel(*:2497)|reschedule(*:2516)))|notifications/([^/]++)/read(*:2554))|pwd/requirements/([^/]++)/reupload(*:2598)|chat/messages/([^/]++)(*:2629))|a(?|pplications/([^/]++)(?|(*:2666))|ics/(?|medical/appointments/([^/]++)(*:2712)|burial/appointments/([^/]++)(*:2749)))|files/([^/]++)/reupload(*:2783)|solo\\-parent/(?|appointments/([^/]++)(?|(*:2832)|/(?|cancel(*:2851)|reschedule(*:2870)))|notifications/([^/]++)/read(*:2908))|pwd/requirements/([^/]++)/reupload(*:2952)|chat/messages/([^/]++)(*:2983)))/?$}sDu',
    ),
    3 => 
    array (
      41 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'analysis.municipality',
          ),
          1 => 
          array (
            0 => 'name',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      77 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'applications.show',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      100 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'applications.requirements',
          ),
          1 => 
          array (
            0 => 'applicationId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      118 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'applications.requirement.upload',
          ),
          1 => 
          array (
            0 => 'applicationId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      132 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'applications.requirement.delete',
          ),
          1 => 
          array (
            0 => 'applicationId',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      156 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'applications.upload-batch',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      194 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.',
          ),
          1 => 
          array (
            0 => 'municipality',
            1 => 'year',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      248 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.status',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      263 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      278 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      299 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      321 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.direct-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      345 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.validate-pwd',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      357 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.validate-aics',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      384 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.mark-id-ready',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      399 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.mark-claimed',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      415 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.mark-released',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      457 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.confirm',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      487 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.cancellation.approve',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      501 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.cancellation.reject',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      519 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.validate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      536 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.reject',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      566 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.reschedule.approve',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      580 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.reschedule.reject',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      593 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      623 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.admin-reschedule',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      637 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      659 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.appointments.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      707 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.deactivate',
          ),
          1 => 
          array (
            0 => 'announcement',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      723 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.activate',
          ),
          1 => 
          array (
            0 => 'announcement',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      732 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.destroy',
          ),
          1 => 
          array (
            0 => 'announcement',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      759 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.barangay',
          ),
          1 => 
          array (
            0 => 'name',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      791 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.view-requirement',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      806 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.update-file-status',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      835 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.serve-file',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      868 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.debug.storage',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      911 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      926 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      941 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      962 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.barangays.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      998 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.programs.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1012 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.programs.delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1047 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.yearly.delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1063 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.yearly.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1079 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.yearly.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1101 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.data.yearly.forceDelete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1138 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.chat.messages',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1170 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.template',
          ),
          1 => 
          array (
            0 => 'type',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1202 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.import-log',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1222 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.import-log.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1238 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.import-log.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1260 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.csv.import-log.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1293 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.yearly.view',
          ),
          1 => 
          array (
            0 => 'year',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1329 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1380 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.resubmit-requirement',
          ),
          1 => 
          array (
            0 => 'fileUploadId',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aCmUlimpLwEYTdGJ',
          ),
          1 => 
          array (
            0 => 'fileUploadId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1407 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.apply',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1444 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.appointments.cancel',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1463 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.appointments.reschedule',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1498 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.messages',
          ),
          1 => 
          array (
            0 => 'adminId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1542 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1565 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users.archived-json',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1593 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1609 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1631 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.users.forceDelete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1698 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.summary.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1714 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.summary.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1736 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.summary.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1746 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.summary.delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1780 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.monthly.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1796 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.monthly.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1812 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.monthly.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1834 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.monthly.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1852 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.municipalities.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1891 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1907 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1923 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1945 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1955 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.barangays.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1996 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.archive',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2012 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2034 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2044 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.data.programs.delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2092 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.restore',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2114 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.force-delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2127 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2148 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.barangays',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.barangays.store',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2159 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.municipalities.delete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2191 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'superadmin.generated::Ei3m7oPJ2O8i4AY9',
          ),
          1 => 
          array (
            0 => 'municipality',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2215 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local.upload',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2254 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'barangay-analysis.show',
          ),
          1 => 
          array (
            0 => 'barangay',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2312 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mMazJG5vKyvCYsvh',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xP5dvZXgZjk2ZF14',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2358 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uVaYHP9DjiyCk1B5',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2395 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Tv4IPeQPd3mMF9nB',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2429 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zfY1Oq5ci1QxTab1',
          ),
          1 => 
          array (
            0 => 'fileId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2478 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zEDIsoT8u1QBN4xH',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2497 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OrAwMCEKuaGk4DoD',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2516 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oV5JtLsW4MVrHMA6',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2554 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PS3E4BIUfyQve25K',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2598 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MN3Wp6HpesoiGeI5',
          ),
          1 => 
          array (
            0 => 'fileId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2629 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::67Dq53JYkWwYBhAV',
          ),
          1 => 
          array (
            0 => 'adminId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2666 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::acam1zerv78SQqMV',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZQTdXoQ31nscwvgB',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2712 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Wrya93dfvbTiHK9a',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2749 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::silWQ7L2ssgpoWPJ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2783 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::h9q6MaoD8SLfrTQ3',
          ),
          1 => 
          array (
            0 => 'fileId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2832 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Tnywgb2Zr6OWhsTk',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2851 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::SSE4P1jRi7CiEUWn',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2870 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::S4FMR7001on4lnKa',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2908 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::250ZGD2JOEae0o58',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2952 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eJp2cLNPdTCrwrXT',
          ),
          1 => 
          array (
            0 => 'fileId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2983 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ssJ3SyQwFkfGSMdk',
          ),
          1 => 
          array (
            0 => 'adminId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ljcKkLijyBOuOrXt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:841:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'C:\\\\xampp\\\\htdocs\\\\MSWDO\\\\mswdo-analysis\\\\vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Foundation\\\\Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"000000000000046d0000000000000000";}}',
        'as' => 'generated::ljcKkLijyBOuOrXt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FD8fDuiFupfNOnRz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:50:"function () {
    return \\redirect(\'/analysis\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000004710000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::FD8fDuiFupfNOnRz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'analysis.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'analysis',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AnalysisController@index',
        'controller' => 'App\\Http\\Controllers\\AnalysisController@index',
        'as' => 'analysis.index',
        'namespace' => NULL,
        'prefix' => '/analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'analysis.municipality' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'analysis/municipality/{name}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AnalysisController@municipality',
        'controller' => 'App\\Http\\Controllers\\AnalysisController@municipality',
        'as' => 'analysis.municipality',
        'namespace' => NULL,
        'prefix' => '/analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'analysis.demographic' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'analysis/demographic',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AnalysisController@demographic',
        'controller' => 'App\\Http\\Controllers\\AnalysisController@demographic',
        'as' => 'analysis.demographic',
        'namespace' => NULL,
        'prefix' => '/analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'analysis.programs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'analysis/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AnalysisController@programs',
        'controller' => 'App\\Http\\Controllers\\AnalysisController@programs',
        'as' => 'analysis.programs',
        'namespace' => NULL,
        'prefix' => '/analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chatbot.reply' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'chatbot/message',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatbotController@reply',
        'controller' => 'App\\Http\\Controllers\\ChatbotController@reply',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chatbot.reply',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@showLoginForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@showLoginForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::K6Bac5OhP931B1t4' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@login',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@login',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::K6Bac5OhP931B1t4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@showRegistrationForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@showRegistrationForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JUXHeI2UE2o5VYdE' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@register',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@register',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::JUXHeI2UE2o5VYdE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'otp.verify.form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\OtpController@showVerifyForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\OtpController@showVerifyForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'otp.verify.form',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'otp.verify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\OtpController@verify',
        'controller' => 'App\\Http\\Controllers\\Auth\\OtpController@verify',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'otp.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'otp.resend' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'resend-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\OtpController@resend',
        'controller' => 'App\\Http\\Controllers\\Auth\\OtpController@resend',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'otp.resend',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.email',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.reset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.change' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ChangePasswordController@showChangeForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ChangePasswordController@showChangeForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.change',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.change.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ChangePasswordController@change',
        'controller' => 'App\\Http\\Controllers\\Auth\\ChangePasswordController@change',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.change.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@logout',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@logout',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.check-username' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'check-username',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@checkUsername',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@checkUsername',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.check-username',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'session.ping' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'session/ping',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:88:"function () {
    return \\response()->json([\'ok\' => true, \'ts\' => \\now()->timestamp]);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000004880000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'session.ping',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@dashboard',
        'controller' => 'App\\Http\\Controllers\\UserController@dashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@dashboard',
        'controller' => 'App\\Http\\Controllers\\UserController@dashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.programs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@programs',
        'controller' => 'App\\Http\\Controllers\\UserController@programs',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.programs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@profile',
        'controller' => 'App\\Http\\Controllers\\UserController@profile',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.profile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'user/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\UserController@updateProfile',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.profile.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.announcements' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/announcements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@announcements',
        'controller' => 'App\\Http\\Controllers\\UserController@announcements',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.announcements',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.my-requirements' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/my-requirements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@myRequirements',
        'controller' => 'App\\Http\\Controllers\\UserController@myRequirements',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.my-requirements',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.resubmit-requirement' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'user/resubmit-requirement/{fileUploadId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resubmitRequirement',
        'controller' => 'App\\Http\\Controllers\\UserController@resubmitRequirement',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.resubmit-requirement',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aCmUlimpLwEYTdGJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/resubmit-requirement/{fileUploadId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resubmitRequirement',
        'controller' => 'App\\Http\\Controllers\\UserController@resubmitRequirement',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::aCmUlimpLwEYTdGJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-category' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/apply/AICS/aics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@aicsCategory',
        'controller' => 'App\\Http\\Controllers\\UserController@aicsCategory',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-category',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-medical' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/apply/AICS/medical',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@aicsMedical',
        'controller' => 'App\\Http\\Controllers\\UserController@aicsMedical',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-medical',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-burial' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/apply/AICS/burial',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@aicsBurial',
        'controller' => 'App\\Http\\Controllers\\UserController@aicsBurial',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-burial',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.apply' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/apply/{program}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@create',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.apply',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.pwd-application' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/pwd-application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@pwdApplication',
        'controller' => 'App\\Http\\Controllers\\UserController@pwdApplication',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.pwd-application',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.pwd-form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/pwd-fillable-form',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@pwdFillableForm',
        'controller' => 'App\\Http\\Controllers\\UserController@pwdFillableForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.pwd-form',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.pwd-form.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/pwd-fillable-form',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@pwdFormSubmit',
        'controller' => 'App\\Http\\Controllers\\UserController@pwdFormSubmit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.pwd-form.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.solo-parent-application' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/solo-parent-application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@soloParentApplication',
        'controller' => 'App\\Http\\Controllers\\UserController@soloParentApplication',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.solo-parent-application',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.pwd-upload-requirement' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/pwd-upload-requirement',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@uploadPwdRequirement',
        'controller' => 'App\\Http\\Controllers\\UserController@uploadPwdRequirement',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.pwd-upload-requirement',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-medical-upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/aics-medical-upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@uploadAicsMedical',
        'controller' => 'App\\Http\\Controllers\\UserController@uploadAicsMedical',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-medical-upload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-burial-upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/aics-burial-upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@uploadAicsBurial',
        'controller' => 'App\\Http\\Controllers\\UserController@uploadAicsBurial',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-burial-upload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-medical-upload-batch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/aics-medical-upload-batch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@uploadAicsMedicalBatch',
        'controller' => 'App\\Http\\Controllers\\UserController@uploadAicsMedicalBatch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-medical-upload-batch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.aics-burial-upload-batch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/aics-burial-upload-batch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@uploadAicsBurialBatch',
        'controller' => 'App\\Http\\Controllers\\UserController@uploadAicsBurialBatch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.aics-burial-upload-batch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.mark-notifications-viewed' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/mark-notifications-viewed',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@markNotificationsViewed',
        'controller' => 'App\\Http\\Controllers\\UserController@markNotificationsViewed',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.mark-notifications-viewed',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.appointments.slots' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/appointments/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\AppointmentController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\AppointmentController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.appointments.slots',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.appointments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\AppointmentController@store',
        'controller' => 'App\\Http\\Controllers\\AppointmentController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.appointments.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.appointments.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/appointments/{id}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\AppointmentController@cancel',
        'controller' => 'App\\Http\\Controllers\\AppointmentController@cancel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.appointments.cancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.appointments.reschedule' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/appointments/{id}/reschedule',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\AppointmentController@requestReschedule',
        'controller' => 'App\\Http\\Controllers\\AppointmentController@requestReschedule',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.appointments.reschedule',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.admins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chat/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getAdmins',
        'controller' => 'App\\Http\\Controllers\\ChatController@getAdmins',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.admins',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chat/messages/{adminId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'controller' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.messages',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'chat/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'controller' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.unread' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chat/unread-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'controller' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.unread',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'solo-parent.apply' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'apply/solo-parent',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@showSoloParentForm',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@showSoloParentForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'solo-parent.apply',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'applications.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@store',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'applications.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'applications.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@show',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'applications.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'applications.requirements' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'applications/{applicationId}/requirements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@showRequirements',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@showRequirements',
        'as' => 'applications.requirements',
        'namespace' => NULL,
        'prefix' => '/applications',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'applications.requirement.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'applications/{applicationId}/requirement/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@uploadRequirement',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@uploadRequirement',
        'as' => 'applications.requirement.upload',
        'namespace' => NULL,
        'prefix' => '/applications',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'applications.requirement.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'applications/{applicationId}/requirement/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'ensure_role:user',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@deleteRequirement',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@deleteRequirement',
        'as' => 'applications.requirement.delete',
        'namespace' => NULL,
        'prefix' => '/applications',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'applications.upload-batch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'applications/upload-batch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => '\\App\\Http\\Controllers\\ApplicationController@uploadBatch',
        'controller' => '\\App\\Http\\Controllers\\ApplicationController@uploadBatch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'applications.upload-batch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdminController@dashboard',
        'controller' => 'App\\Http\\Controllers\\SuperAdminController@dashboard',
        'as' => 'superadmin.dashboard',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@index',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@index',
        'as' => 'superadmin.users',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@store',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@store',
        'as' => 'superadmin.users.create',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'superadmin/users/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@update',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@update',
        'as' => 'superadmin.users.update',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users.archived-json' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/users/archived-json',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@getArchivedUsers',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@getArchivedUsers',
        'as' => 'superadmin.users.archived-json',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users.archive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/users/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@archive',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@archive',
        'as' => 'superadmin.users.archive',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/users/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@restore',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@restore',
        'as' => 'superadmin.users.restore',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.users.forceDelete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/users/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@forceDelete',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\UserController@forceDelete',
        'as' => 'superadmin.users.forceDelete',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@dashboard',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@dashboard',
        'as' => 'superadmin.data.dashboard',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/municipalities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@municipalities',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@municipalities',
        'as' => 'superadmin.data.municipalities',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.summary.save' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/summary/save',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@saveMunicipalitySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@saveMunicipalitySummary',
        'as' => 'superadmin.data.municipalities.summary.save',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.summary.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/municipalities/summary/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedSummaries',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedSummaries',
        'as' => 'superadmin.data.municipalities.summary.archived',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.summary.archive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/summary/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveMunicipalitySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveMunicipalitySummary',
        'as' => 'superadmin.data.municipalities.summary.archive',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.summary.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/summary/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreMunicipalitySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreMunicipalitySummary',
        'as' => 'superadmin.data.municipalities.summary.restore',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.summary.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/municipalities/summary/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteMunicipalitySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteMunicipalitySummary',
        'as' => 'superadmin.data.municipalities.summary.force-delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.summary.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/municipalities/summary/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@deleteMunicipalitySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@deleteMunicipalitySummary',
        'as' => 'superadmin.data.municipalities.summary.delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.monthly.save' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/monthly/save',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@saveMonthlySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@saveMonthlySummary',
        'as' => 'superadmin.data.municipalities.monthly.save',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.monthly.edit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/monthly/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@editMonthlySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@editMonthlySummary',
        'as' => 'superadmin.data.municipalities.monthly.edit',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.monthly.archive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/monthly/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveMonthlySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveMonthlySummary',
        'as' => 'superadmin.data.municipalities.monthly.archive',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.monthly.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/monthly/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreMonthlySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreMonthlySummary',
        'as' => 'superadmin.data.municipalities.monthly.restore',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.monthly.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/municipalities/monthly/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteMonthlySummary',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteMonthlySummary',
        'as' => 'superadmin.data.municipalities.monthly.force-delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.monthly.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/municipalities/monthly/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedMonthly',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedMonthly',
        'as' => 'superadmin.data.municipalities.monthly.archived',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.municipalities.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/municipalities/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@updateMunicipality',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@updateMunicipality',
        'as' => 'superadmin.data.municipalities.update',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@barangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@barangays',
        'as' => 'superadmin.data.barangays',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@storeBarangay',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@storeBarangay',
        'as' => 'superadmin.data.barangays.store',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/barangays/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@editBarangay',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@editBarangay',
        'as' => 'superadmin.data.barangays.edit',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/barangays/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@bulkArchiveBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@bulkArchiveBarangays',
        'as' => 'superadmin.data.barangays.bulk-delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.bulk-store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/barangays/bulk-store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@bulkStoreBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@bulkStoreBarangays',
        'as' => 'superadmin.data.barangays.bulk-store',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/barangays/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedBarangays',
        'as' => 'superadmin.data.barangays.archived',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.archived.delete-all' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/barangays/archived/delete-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteAllArchivedBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteAllArchivedBarangays',
        'as' => 'superadmin.data.barangays.archived.delete-all',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.archive' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'superadmin/data/barangays/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveBarangay',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveBarangay',
        'as' => 'superadmin.data.barangays.archive',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.restore' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'superadmin/data/barangays/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreBarangay',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreBarangay',
        'as' => 'superadmin.data.barangays.restore',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/barangays/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteBarangay',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteBarangay',
        'as' => 'superadmin.data.barangays.force-delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.bulk-update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/barangays/bulk-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@bulkUpdateBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@bulkUpdateBarangays',
        'as' => 'superadmin.data.barangays.bulk-update',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.barangays.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/barangays/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@updateBarangay',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@updateBarangay',
        'as' => 'superadmin.data.barangays.update',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@programs',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@programs',
        'as' => 'superadmin.data.programs',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.sync-barangays' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/programs/sync-barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@syncFromBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@syncFromBarangays',
        'as' => 'superadmin.data.programs.sync-barangays',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/programs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@createProgram',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@createProgram',
        'as' => 'superadmin.data.programs.create',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/data/programs/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedPrograms',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@getArchivedPrograms',
        'as' => 'superadmin.data.programs.archived',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.archive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/programs/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveProgram',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@archiveProgram',
        'as' => 'superadmin.data.programs.archive',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/programs/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreProgram',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@restoreProgram',
        'as' => 'superadmin.data.programs.restore',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/programs/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteProgram',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@forceDeleteProgram',
        'as' => 'superadmin.data.programs.force-delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/data/programs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@updateProgram',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@updateProgram',
        'as' => 'superadmin.data.programs.update',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.data.programs.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/data/programs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@deleteProgram',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\DataManagementController@deleteProgram',
        'as' => 'superadmin.data.programs.delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/municipalities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@index',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@index',
        'as' => 'superadmin.municipalities.index',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/municipalities/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@create',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@create',
        'as' => 'superadmin.municipalities.create',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/municipalities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@store',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@store',
        'as' => 'superadmin.municipalities.store',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/municipalities/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@getArchived',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@getArchived',
        'as' => 'superadmin.municipalities.archived',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/municipalities/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@restore',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@restore',
        'as' => 'superadmin.municipalities.restore',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/municipalities/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@forceDestroy',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@forceDestroy',
        'as' => 'superadmin.municipalities.force-delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/municipalities/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@edit',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@edit',
        'as' => 'superadmin.municipalities.edit',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'superadmin/municipalities/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@update',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@update',
        'as' => 'superadmin.municipalities.update',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'superadmin/municipalities/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@destroy',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@destroy',
        'as' => 'superadmin.municipalities.delete',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.barangays' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/municipalities/{id}/barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@showBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@showBarangays',
        'as' => 'superadmin.municipalities.barangays',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.municipalities.barangays.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'superadmin/municipalities/{id}/barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@storeBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@storeBarangays',
        'as' => 'superadmin.municipalities.barangays.store',
        'namespace' => NULL,
        'prefix' => 'superadmin/municipalities',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/api/laguna-municipalities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@getLagunaMunicipalities',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@getLagunaMunicipalities',
        'as' => 'superadmin.',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'superadmin.generated::Ei3m7oPJ2O8i4AY9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'superadmin/api/barangays/{municipality}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:super_admin',
        ),
        'uses' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@getBarangays',
        'controller' => 'App\\Http\\Controllers\\SuperAdmin\\MunicipalityManagementController@getBarangays',
        'as' => 'superadmin.generated::Ei3m7oPJ2O8i4AY9',
        'namespace' => NULL,
        'prefix' => '/superadmin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@dashboard',
        'controller' => 'App\\Http\\Controllers\\AdminController@dashboard',
        'as' => 'admin.dashboard',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.detailed-analysis' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/detailed-analysis',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@detailedAnalysis',
        'controller' => 'App\\Http\\Controllers\\AdminController@detailedAnalysis',
        'as' => 'admin.detailed-analysis',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@applications',
        'controller' => 'App\\Http\\Controllers\\AdminController@applications',
        'as' => 'admin.applications',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/applications/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@updateApplicationStatus',
        'controller' => 'App\\Http\\Controllers\\AdminController@updateApplicationStatus',
        'as' => 'admin.applications.status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.barangay' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/barangay/{name}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@barangay',
        'controller' => 'App\\Http\\Controllers\\AdminController@barangay',
        'as' => 'admin.barangay',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.requirements' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/requirements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@requirements',
        'controller' => 'App\\Http\\Controllers\\AdminController@requirements',
        'as' => 'admin.requirements',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.view-requirement' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/requirements/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@viewRequirement',
        'controller' => 'App\\Http\\Controllers\\AdminController@viewRequirement',
        'as' => 'admin.view-requirement',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.update-file-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/requirements/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@updateFileStatus',
        'controller' => 'App\\Http\\Controllers\\AdminController@updateFileStatus',
        'as' => 'admin.update-file-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.serve-file' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/files/{id}/serve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@serveFile',
        'controller' => 'App\\Http\\Controllers\\AdminController@serveFile',
        'as' => 'admin.serve-file',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.debug.storage' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/debug/storage/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:851:"function ($id) {
        $fu = \\App\\Models\\FileUpload::findOrFail($id);
        $fp = $fu->file_path;
        return \\response()->json([
            \'file_path\' => $fp,
            \'base_path\' => \\base_path(),
            \'storage_path\' => \\storage_path(\'app/public\'),
            \'public_path\' => \\public_path(),
            \'disk_path\' => \\Illuminate\\Support\\Facades\\Storage::disk(\'public\')->path($fp ?? \'\'),
            \'disk_exists\' => $fp ? \\Illuminate\\Support\\Facades\\Storage::disk(\'public\')->exists($fp) : false,
            \'file_exists_1\' => $fp ? \\file_exists(\\Illuminate\\Support\\Facades\\Storage::disk(\'public\')->path($fp)) : false,
            \'file_exists_2\' => $fp ? \\file_exists(\\base_path(\'storage/app/public/\' . $fp)) : false,
            \'file_exists_3\' => $fp ? \\file_exists(\\public_path(\'storage/\' . $fp)) : false,
        ]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000004f30000000000000000";}}',
        'as' => 'admin.debug.storage',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.search' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@searchUsers',
        'controller' => 'App\\Http\\Controllers\\AdminController@searchUsers',
        'as' => 'admin.users.search',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.archive' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/applications/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@archiveApplication',
        'controller' => 'App\\Http\\Controllers\\AdminController@archiveApplication',
        'as' => 'admin.applications.archive',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.restore' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/applications/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@restoreApplication',
        'controller' => 'App\\Http\\Controllers\\AdminController@restoreApplication',
        'as' => 'admin.applications.restore',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/applications/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@forceDeleteApplication',
        'controller' => 'App\\Http\\Controllers\\AdminController@forceDeleteApplication',
        'as' => 'admin.applications.force-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.direct-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/applications/{id}/direct-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@directDeleteApplication',
        'controller' => 'App\\Http\\Controllers\\AdminController@directDeleteApplication',
        'as' => 'admin.applications.direct-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.validate-pwd' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/applications/{id}/validate-pwd',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@validatePwdApplication',
        'controller' => 'App\\Http\\Controllers\\AdminController@validatePwdApplication',
        'as' => 'admin.applications.validate-pwd',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.validate-aics' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/applications/{id}/validate-aics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@validateAicsApplication',
        'controller' => 'App\\Http\\Controllers\\AdminController@validateAicsApplication',
        'as' => 'admin.applications.validate-aics',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.mark-id-ready' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/applications/{id}/mark-id-ready',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@markIdReady',
        'controller' => 'App\\Http\\Controllers\\AdminController@markIdReady',
        'as' => 'admin.applications.mark-id-ready',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.mark-claimed' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/applications/{id}/mark-claimed',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@markClaimed',
        'controller' => 'App\\Http\\Controllers\\AdminController@markClaimed',
        'as' => 'admin.applications.mark-claimed',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.mark-released' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/applications/{id}/mark-released',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@markReleased',
        'controller' => 'App\\Http\\Controllers\\AdminController@markReleased',
        'as' => 'admin.applications.mark-released',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@index',
        'as' => 'admin.admin.appointments.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.slots' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/appointments/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AppointmentController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\AppointmentController@getAvailableSlots',
        'as' => 'admin.admin.appointments.slots',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@confirm',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@confirm',
        'as' => 'admin.admin.appointments.confirm',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.validate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/validate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@validate',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@validate',
        'as' => 'admin.admin.appointments.validate',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@reject',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@reject',
        'as' => 'admin.admin.appointments.reject',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.reschedule.approve' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/reschedule/approve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@approveReschedule',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@approveReschedule',
        'as' => 'admin.admin.appointments.reschedule.approve',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.reschedule.reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/reschedule/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@rejectReschedule',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@rejectReschedule',
        'as' => 'admin.admin.appointments.reschedule.reject',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.admin-reschedule' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/admin-reschedule',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@adminReschedule',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@adminReschedule',
        'as' => 'admin.admin.appointments.admin-reschedule',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.cancellation.approve' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/cancellation/approve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@approveCancellation',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@approveCancellation',
        'as' => 'admin.admin.appointments.cancellation.approve',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.cancellation.reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/appointments/{id}/cancellation/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@rejectCancellation',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@rejectCancellation',
        'as' => 'admin.admin.appointments.cancellation.reject',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.archive' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/appointments/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@archive',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@archive',
        'as' => 'admin.admin.appointments.archive',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.restore' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/appointments/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@restore',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@restore',
        'as' => 'admin.admin.appointments.restore',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/appointments/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@forceDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@forceDelete',
        'as' => 'admin.admin.appointments.force-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.appointments.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/appointments/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AppointmentController@archived',
        'controller' => 'App\\Http\\Controllers\\Admin\\AppointmentController@archived',
        'as' => 'admin.admin.appointments.archived',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.chat.users' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/chat/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getUsers',
        'controller' => 'App\\Http\\Controllers\\ChatController@getUsers',
        'as' => 'admin.chat.users',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.chat.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/chat/messages/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'controller' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'as' => 'admin.chat.messages',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.chat.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/chat/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'controller' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'as' => 'admin.chat.send',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.chat.unread' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/chat/unread-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'controller' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'as' => 'admin.chat.unread',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminSettingsController@index',
        'controller' => 'App\\Http\\Controllers\\AdminSettingsController@index',
        'as' => 'admin.settings',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.get' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/settings/get',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminSettingsController@get',
        'controller' => 'App\\Http\\Controllers\\AdminSettingsController@get',
        'as' => 'admin.settings.get',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/settings/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminSettingsController@update',
        'controller' => 'App\\Http\\Controllers\\AdminSettingsController@update',
        'as' => 'admin.settings.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.reset' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/settings/reset',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminSettingsController@reset',
        'controller' => 'App\\Http\\Controllers\\AdminSettingsController@reset',
        'as' => 'admin.settings.reset',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@dashboard',
        'as' => 'admin.data.dashboard',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.municipality' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data/municipality',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@municipality',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@municipality',
        'as' => 'admin.data.municipality',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.municipality.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/municipality/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@updateMunicipality',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@updateMunicipality',
        'as' => 'admin.data.municipality.update',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data/barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@barangays',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@barangays',
        'as' => 'admin.data.barangays',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.bulk-update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/barangays/bulk-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@bulkUpdateBarangays',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@bulkUpdateBarangays',
        'as' => 'admin.data.barangays.bulk-update',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.bulk-store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/barangays/bulk-store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@bulkStoreBarangays',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@bulkStoreBarangays',
        'as' => 'admin.data.barangays.bulk-store',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.find-or-create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/barangays/find-or-create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@findOrCreateBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@findOrCreateBarangay',
        'as' => 'admin.data.barangays.find-or-create',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/barangays/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@updateBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@updateBarangay',
        'as' => 'admin.data.barangays.update',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.programs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@programs',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@programs',
        'as' => 'admin.data.programs',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.programs.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/programs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@createProgram',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@createProgram',
        'as' => 'admin.data.programs.create',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.programs.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/programs/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@updateProgram',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@updateProgram',
        'as' => 'admin.data.programs.update',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.programs.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/data/programs/{id}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@deleteProgram',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@deleteProgram',
        'as' => 'admin.data.programs.delete',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.yearly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data/yearly',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@yearlyData',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@yearlyData',
        'as' => 'admin.data.yearly',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.yearly.save' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/yearly/save',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@saveYearlySummary',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@saveYearlySummary',
        'as' => 'admin.data.yearly.save',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.yearly.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/data/yearly/{id}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@deleteYearlySummary',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@deleteYearlySummary',
        'as' => 'admin.data.yearly.delete',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.yearly.archive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/yearly/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@archiveYearlySummary',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@archiveYearlySummary',
        'as' => 'admin.data.yearly.archive',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.yearly.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/yearly/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@restoreYearlySummary',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@restoreYearlySummary',
        'as' => 'admin.data.yearly.restore',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.yearly.forceDelete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/data/yearly/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@forceDeleteYearlySummary',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@forceDeleteYearlySummary',
        'as' => 'admin.data.yearly.forceDelete',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.import' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/csv/import',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@import',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@import',
        'as' => 'admin.csv.import',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.export' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/csv/export',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@export',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@export',
        'as' => 'admin.csv.export',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.template' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/csv/template/{type}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@downloadTemplate',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@downloadTemplate',
        'as' => 'admin.csv.template',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.import-log.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/csv/import-log/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@getArchivedLogs',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@getArchivedLogs',
        'as' => 'admin.csv.import-log.archived',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.import-log' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/csv/import-log/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@getImportLog',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@getImportLog',
        'as' => 'admin.csv.import-log',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.import-log.archive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/csv/import-log/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@archiveLog',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@archiveLog',
        'as' => 'admin.csv.import-log.archive',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.import-log.restore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/csv/import-log/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@restoreLog',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@restoreLog',
        'as' => 'admin.csv.import-log.restore',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.csv.import-log.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/csv/import-log/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@forceDeleteLog',
        'controller' => 'App\\Http\\Controllers\\Admin\\CsvImportExportController@forceDeleteLog',
        'as' => 'admin.csv.import-log.force-delete',
        'namespace' => NULL,
        'prefix' => 'admin/csv',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/announcements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@index',
        'as' => 'admin.announcements.index',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/announcements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@store',
        'as' => 'admin.announcements.store',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.deactivate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/announcements/{announcement}/deactivate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@deactivate',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@deactivate',
        'as' => 'admin.announcements.deactivate',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.activate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/announcements/{announcement}/activate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@activate',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@activate',
        'as' => 'admin.announcements.activate',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/announcements/{announcement}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@destroy',
        'as' => 'admin.announcements.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.mark-notifications-viewed' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/mark-notifications-viewed',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@markNotificationsViewed',
        'controller' => 'App\\Http\\Controllers\\AdminController@markNotificationsViewed',
        'as' => 'admin.mark-notifications-viewed',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vision-mission.save' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/vision-mission/save',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@saveVisionMission',
        'controller' => 'App\\Http\\Controllers\\AdminController@saveVisionMission',
        'as' => 'admin.vision-mission.save',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@users',
        'controller' => 'App\\Http\\Controllers\\AdminController@users',
        'as' => 'admin.users',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.yearly.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/yearly',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\YearlyComparisonController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\YearlyComparisonController@index',
        'as' => 'admin.yearly.index',
        'namespace' => NULL,
        'prefix' => 'admin/yearly',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.yearly.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/yearly/view/{year}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\YearlyComparisonController@viewYear',
        'controller' => 'App\\Http\\Controllers\\Admin\\YearlyComparisonController@viewYear',
        'as' => 'admin.yearly.view',
        'namespace' => NULL,
        'prefix' => 'admin/yearly',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.yearly.compare' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/yearly/compare',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\YearlyComparisonController@compare',
        'controller' => 'App\\Http\\Controllers\\Admin\\YearlyComparisonController@compare',
        'as' => 'admin.yearly.compare',
        'namespace' => NULL,
        'prefix' => 'admin/yearly',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'barangay-analysis.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'barangay-analysis',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@index',
        'as' => 'barangay-analysis.index',
        'namespace' => NULL,
        'prefix' => '/barangay-analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'barangay-analysis.programs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'barangay-analysis/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@programs',
        'controller' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@programs',
        'as' => 'barangay-analysis.programs',
        'namespace' => NULL,
        'prefix' => '/barangay-analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'barangay-analysis.applicants' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'barangay-analysis/applicants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@applicants',
        'controller' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@applicants',
        'as' => 'barangay-analysis.applicants',
        'namespace' => NULL,
        'prefix' => '/barangay-analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'barangay-analysis.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'barangay-analysis/{barangay}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@showBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\BarangayAnalysisController@showBarangay',
        'as' => 'barangay-analysis.show',
        'namespace' => NULL,
        'prefix' => '/barangay-analysis',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/barangays/{municipality}/{year}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\BarangayDataController@getByYear',
        'controller' => 'App\\Http\\Controllers\\BarangayDataController@getByYear',
        'as' => 'api.',
        'namespace' => NULL,
        'prefix' => '/api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Zi3bpHUDR658v2Om' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'fix-database',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:516:"function () {
    try {
        \\Illuminate\\Support\\Facades\\Artisan::call(\'config:clear\');
        \\Illuminate\\Support\\Facades\\Artisan::call(\'cache:clear\');
        \\Illuminate\\Support\\Facades\\Artisan::call(\'migrate:fresh\', [\'--force\' => true]);
        
        return "Database successfully rebuilt, cache cleared, and personal_access_tokens table created! You can now register a new account and log into the app.";
    } catch (\\Exception $e) {
        return "Error fixing database: " . $e->getMessage();
    }
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000004870000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::Zi3bpHUDR658v2Om',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/barangays',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@storeBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@storeBarangay',
        'as' => 'admin.data.barangays.store',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.archived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data/barangays/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@getArchivedBarangays',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@getArchivedBarangays',
        'as' => 'admin.data.barangays.archived',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/data/barangays/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@bulkArchiveBarangays',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@bulkArchiveBarangays',
        'as' => 'admin.data.barangays.bulk-delete',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.archived.delete-all' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/data/barangays/archived/delete-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@forceDeleteAllArchivedBarangays',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@forceDeleteAllArchivedBarangays',
        'as' => 'admin.data.barangays.archived.delete-all',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.archive' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/data/barangays/{id}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@archiveBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@archiveBarangay',
        'as' => 'admin.data.barangays.archive',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.restore' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/data/barangays/{id}/restore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@restoreBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@restoreBarangay',
        'as' => 'admin.data.barangays.restore',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.data.barangays.force-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/data/barangays/{id}/force-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DataManagementController@forceDeleteBarangay',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataManagementController@forceDeleteBarangay',
        'as' => 'admin.data.barangays.force-delete',
        'namespace' => NULL,
        'prefix' => 'admin/data',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::D0D3jXgTasD6hmck' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/municipalities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@municipalities',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@municipalities',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::D0D3jXgTasD6hmck',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::O3Dis0Smizi3HTpU' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:3,1',
        ),
        'uses' => '\\App\\Http\\Controllers\\Api\\MobileApiController@register',
        'controller' => '\\App\\Http\\Controllers\\Api\\MobileApiController@register',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::O3Dis0Smizi3HTpU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jgI7fuGMWVouZpUC' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:10,1',
        ),
        'uses' => '\\App\\Http\\Controllers\\Api\\MobileApiController@login',
        'controller' => '\\App\\Http\\Controllers\\Api\\MobileApiController@login',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::jgI7fuGMWVouZpUC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ejJrJ4POM9y8Hs1R' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@verifyOtp',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@verifyOtp',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::ejJrJ4POM9y8Hs1R',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yYi8TwRcvqWMyvKX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/resend-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@resendOtp',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@resendOtp',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::yYi8TwRcvqWMyvKX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::z88mlgEnkGUQXUxK' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@forgotPassword',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@forgotPassword',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::z88mlgEnkGUQXUxK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AemqP1APfY3q9vlY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@resetPassword',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@resetPassword',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::AemqP1APfY3q9vlY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AKSPdQyrHefwJCxi' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/chatbot/message',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:10,1',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatbotController@reply',
        'controller' => 'App\\Http\\Controllers\\ChatbotController@reply',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::AKSPdQyrHefwJCxi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WCMyQeDvaKBba7gU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@user',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@user',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::WCMyQeDvaKBba7gU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZVfr1oD53P0Vt9An' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'mobile-api/v1/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@updateUserProfile',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@updateUserProfile',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::ZVfr1oD53P0Vt9An',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aVL5arvMHr6ImlGu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@changePassword',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@changePassword',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::aVL5arvMHr6ImlGu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::D8SWB9ZwqbN8kkk8' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@dashboard',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::D8SWB9ZwqbN8kkk8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::67ejE419FtUW5axy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/announcements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@announcements',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@announcements',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::67ejE419FtUW5axy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mAYRClxSBYN3NNh7' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@notifications',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@notifications',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::mAYRClxSBYN3NNh7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ChYOF6XbOrAcQb8a' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/notifications/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@markNotificationsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@markNotificationsRead',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::ChYOF6XbOrAcQb8a',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6iBRN9Y0qdepyxyK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@applications',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@applications',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::6iBRN9Y0qdepyxyK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mMazJG5vKyvCYsvh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@applicationDetail',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@applicationDetail',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::mMazJG5vKyvCYsvh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Vw6MJrJjZnZ90CXq' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@submitApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@submitApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::Vw6MJrJjZnZ90CXq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xP5dvZXgZjk2ZF14' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'mobile-api/v1/applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@resubmitApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@resubmitApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::xP5dvZXgZjk2ZF14',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zfY1Oq5ci1QxTab1' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/files/{fileId}/reupload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@reuploadFile',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@reuploadFile',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::zfY1Oq5ci1QxTab1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uXNj0Qgloat5kuiH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@logout',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@logout',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::uXNj0Qgloat5kuiH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q69lZ4gnrTsVVk0P' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/solo-parent/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::Q69lZ4gnrTsVVk0P',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uib1k9YVPgIiS8th' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/solo-parent/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@bookAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@bookAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::uib1k9YVPgIiS8th',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::W4dTsxRBblE2nsIY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/solo-parent/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAppointments',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAppointments',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::W4dTsxRBblE2nsIY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zEDIsoT8u1QBN4xH' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/v1/solo-parent/appointments/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@cancelAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@cancelAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::zEDIsoT8u1QBN4xH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OrAwMCEKuaGk4DoD' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/solo-parent/appointments/{id}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestCancellation',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestCancellation',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::OrAwMCEKuaGk4DoD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oV5JtLsW4MVrHMA6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/solo-parent/appointments/{id}/reschedule',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestReschedule',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestReschedule',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::oV5JtLsW4MVrHMA6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZnN9v6VA6tyoeqgf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/solo-parent/application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::ZnN9v6VA6tyoeqgf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yBX6hMpWYA8TPN5j' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/solo-parent/requirements/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@uploadRequirement',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@uploadRequirement',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::yBX6hMpWYA8TPN5j',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::68uLe06jqrY56bp3' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/solo-parent/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getNotifications',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getNotifications',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::68uLe06jqrY56bp3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PS3E4BIUfyQve25K' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'mobile-api/v1/solo-parent/notifications/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@markNotificationRead',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@markNotificationRead',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::PS3E4BIUfyQve25K',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::w0qH5LAb1UUd8Ypp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/aics/medical/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::w0qH5LAb1UUd8Ypp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3qs8aG5BovK1CupJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/aics/medical/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookMedicalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookMedicalAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::3qs8aG5BovK1CupJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2RxEXD84fKxm9UKo' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/aics/medical/appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getMedicalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getMedicalAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::2RxEXD84fKxm9UKo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uVaYHP9DjiyCk1B5' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/v1/aics/medical/appointments/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelMedicalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelMedicalAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::uVaYHP9DjiyCk1B5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KMso42umnZOE0Uaq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/aics/burial/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::KMso42umnZOE0Uaq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gu5bXVUszEPxtQMs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/aics/burial/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookBurialAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookBurialAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::gu5bXVUszEPxtQMs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zOLkD4erIhPQoOtS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/aics/burial/appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getBurialAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getBurialAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::zOLkD4erIhPQoOtS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Tv4IPeQPd3mMF9nB' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/v1/aics/burial/appointments/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelBurialAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelBurialAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::Tv4IPeQPd3mMF9nB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PKsxdUCIM3myNt4m' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/pwd/application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PwdApiController@getApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\PwdApiController@getApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/pwd',
        'where' => 
        array (
        ),
        'as' => 'generated::PKsxdUCIM3myNt4m',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cVK2jUPpsJa9ZxAQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/pwd/requirements/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PwdApiController@uploadRequirement',
        'controller' => 'App\\Http\\Controllers\\Api\\PwdApiController@uploadRequirement',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/pwd',
        'where' => 
        array (
        ),
        'as' => 'generated::cVK2jUPpsJa9ZxAQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MN3Wp6HpesoiGeI5' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/pwd/requirements/{fileId}/reupload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PwdApiController@reuploadRequirement',
        'controller' => 'App\\Http\\Controllers\\Api\\PwdApiController@reuploadRequirement',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/pwd',
        'where' => 
        array (
        ),
        'as' => 'generated::MN3Wp6HpesoiGeI5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::H365PdxPwVGOS1LW' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/device-token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@registerDeviceToken',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@registerDeviceToken',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::H365PdxPwVGOS1LW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vZ0dhGwiMeeqRrHt' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/v1/device-token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@removeDeviceToken',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@removeDeviceToken',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::vZ0dhGwiMeeqRrHt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tjsYRh0VRvg4FHHT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/chat/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getAdmins',
        'controller' => 'App\\Http\\Controllers\\ChatController@getAdmins',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::tjsYRh0VRvg4FHHT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::67Dq53JYkWwYBhAV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/chat/messages/{adminId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'controller' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::67Dq53JYkWwYBhAV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0K73A4gYgZh24Phj' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/v1/chat/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'controller' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::0K73A4gYgZh24Phj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EaxR7fIQpNfSUtbp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/v1/chat/unread-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'controller' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'namespace' => NULL,
        'prefix' => 'mobile-api/v1/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::EaxR7fIQpNfSUtbp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::67FSxhvHEaDWHdSJ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/municipalities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@municipalities',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@municipalities',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::67FSxhvHEaDWHdSJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xGefmKTJShzvztFz' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:3,1',
        ),
        'uses' => '\\App\\Http\\Controllers\\Api\\MobileApiController@register',
        'controller' => '\\App\\Http\\Controllers\\Api\\MobileApiController@register',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::xGefmKTJShzvztFz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NjbKOb53McjQLdif' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:10,1',
        ),
        'uses' => '\\App\\Http\\Controllers\\Api\\MobileApiController@login',
        'controller' => '\\App\\Http\\Controllers\\Api\\MobileApiController@login',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::NjbKOb53McjQLdif',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::C9TVhbv19JQ0wDvv' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@verifyOtp',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@verifyOtp',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::C9TVhbv19JQ0wDvv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::c4XBScjCjGMQVRLH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/resend-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@resendOtp',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@resendOtp',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::c4XBScjCjGMQVRLH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::E9yecjYHTou7GlDu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@forgotPassword',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@forgotPassword',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::E9yecjYHTou7GlDu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7LSbr6GvsL2A8jeq' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:5,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@resetPassword',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@resetPassword',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::7LSbr6GvsL2A8jeq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Nr1WsyZULsnafXzc' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/chatbot/message',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'throttle:10,1',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatbotController@reply',
        'controller' => 'App\\Http\\Controllers\\ChatbotController@reply',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::Nr1WsyZULsnafXzc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y5FqaFUTbh4KD7Ss' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@user',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@user',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::y5FqaFUTbh4KD7Ss',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fuziMjbnocTWEVdW' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'mobile-api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@updateUserProfile',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@updateUserProfile',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::fuziMjbnocTWEVdW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mT7mPDZywhDDl7Hz' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@changePassword',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@changePassword',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::mT7mPDZywhDDl7Hz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rGd3eH9q7zQd5FYw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@dashboard',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::rGd3eH9q7zQd5FYw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4qJgJjzoJBQmeWcm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/announcements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@announcements',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@announcements',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::4qJgJjzoJBQmeWcm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KqoTkfTedgx7jkX6' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@notifications',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@notifications',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::KqoTkfTedgx7jkX6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yF8syUeLI59UX8Ng' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/notifications/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@markNotificationsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@markNotificationsRead',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::yF8syUeLI59UX8Ng',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::56X5ZAORTEpBegbw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@applications',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@applications',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::56X5ZAORTEpBegbw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::acam1zerv78SQqMV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@applicationDetail',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@applicationDetail',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::acam1zerv78SQqMV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RKf39wfB6RkncmoI' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@submitApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@submitApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::RKf39wfB6RkncmoI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZQTdXoQ31nscwvgB' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'mobile-api/applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@resubmitApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@resubmitApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::ZQTdXoQ31nscwvgB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::h9q6MaoD8SLfrTQ3' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/files/{fileId}/reupload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@reuploadFile',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@reuploadFile',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::h9q6MaoD8SLfrTQ3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FOFJLvpSqs3PBo1z' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@logout',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@logout',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::FOFJLvpSqs3PBo1z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qK2eZHIzQlXB0bdA' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/solo-parent/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::qK2eZHIzQlXB0bdA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VGcyZmR9lX8CODRw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/solo-parent/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@bookAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@bookAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::VGcyZmR9lX8CODRw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3TUvqwcMj2gBwZq4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/solo-parent/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAppointments',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getAppointments',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::3TUvqwcMj2gBwZq4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Tnywgb2Zr6OWhsTk' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/solo-parent/appointments/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@cancelAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@cancelAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::Tnywgb2Zr6OWhsTk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SSE4P1jRi7CiEUWn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/solo-parent/appointments/{id}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestCancellation',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestCancellation',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::SSE4P1jRi7CiEUWn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::S4FMR7001on4lnKa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/solo-parent/appointments/{id}/reschedule',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestReschedule',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@requestReschedule',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::S4FMR7001on4lnKa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QHqOcfXQPbQaPzHv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/solo-parent/application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::QHqOcfXQPbQaPzHv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yE9UN3EGnh7jaxi3' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/solo-parent/requirements/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@uploadRequirement',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@uploadRequirement',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::yE9UN3EGnh7jaxi3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CrKWn5JEXFW3eTyK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/solo-parent/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getNotifications',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@getNotifications',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::CrKWn5JEXFW3eTyK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::250ZGD2JOEae0o58' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'mobile-api/solo-parent/notifications/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@markNotificationRead',
        'controller' => 'App\\Http\\Controllers\\Api\\SoloParentApiController@markNotificationRead',
        'namespace' => NULL,
        'prefix' => 'mobile-api/solo-parent',
        'where' => 
        array (
        ),
        'as' => 'generated::250ZGD2JOEae0o58',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LY3IX7VJyAoEgBeV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/aics/medical/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::LY3IX7VJyAoEgBeV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LPes6Yj5B8nuYDtW' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/aics/medical/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookMedicalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookMedicalAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::LPes6Yj5B8nuYDtW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mUmrbYn1tHlD8kRc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/aics/medical/appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getMedicalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getMedicalAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::mUmrbYn1tHlD8kRc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Wrya93dfvbTiHK9a' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/aics/medical/appointments/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelMedicalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelMedicalAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/medical',
        'where' => 
        array (
        ),
        'as' => 'generated::Wrya93dfvbTiHK9a',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NGd6GgWyVRT44yzG' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/aics/burial/slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getAvailableSlots',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::NGd6GgWyVRT44yzG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jT7layhW0QCOmh3q' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/aics/burial/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookBurialAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@bookBurialAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::jT7layhW0QCOmh3q',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vuVKC4Jnb3vYyLP2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/aics/burial/appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@getBurialAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@getBurialAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::vuVKC4Jnb3vYyLP2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::silWQ7L2ssgpoWPJ' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/aics/burial/appointments/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelBurialAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\AicsApiController@cancelBurialAppointment',
        'namespace' => NULL,
        'prefix' => 'mobile-api/aics/burial',
        'where' => 
        array (
        ),
        'as' => 'generated::silWQ7L2ssgpoWPJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JBZwvuYmqA3KHCiX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/pwd/application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PwdApiController@getApplication',
        'controller' => 'App\\Http\\Controllers\\Api\\PwdApiController@getApplication',
        'namespace' => NULL,
        'prefix' => 'mobile-api/pwd',
        'where' => 
        array (
        ),
        'as' => 'generated::JBZwvuYmqA3KHCiX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::066QShTtsHqd5y8E' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/pwd/requirements/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PwdApiController@uploadRequirement',
        'controller' => 'App\\Http\\Controllers\\Api\\PwdApiController@uploadRequirement',
        'namespace' => NULL,
        'prefix' => 'mobile-api/pwd',
        'where' => 
        array (
        ),
        'as' => 'generated::066QShTtsHqd5y8E',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eJp2cLNPdTCrwrXT' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/pwd/requirements/{fileId}/reupload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PwdApiController@reuploadRequirement',
        'controller' => 'App\\Http\\Controllers\\Api\\PwdApiController@reuploadRequirement',
        'namespace' => NULL,
        'prefix' => 'mobile-api/pwd',
        'where' => 
        array (
        ),
        'as' => 'generated::eJp2cLNPdTCrwrXT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0uMdCIfbYvHRmv1h' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/device-token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@registerDeviceToken',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@registerDeviceToken',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::0uMdCIfbYvHRmv1h',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1spvGQhWHOXZx3t1' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'mobile-api/device-token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileApiController@removeDeviceToken',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileApiController@removeDeviceToken',
        'namespace' => NULL,
        'prefix' => 'mobile-api',
        'where' => 
        array (
        ),
        'as' => 'generated::1spvGQhWHOXZx3t1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iO3xXL6nKPDQzT8X' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/chat/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getAdmins',
        'controller' => 'App\\Http\\Controllers\\ChatController@getAdmins',
        'namespace' => NULL,
        'prefix' => 'mobile-api/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::iO3xXL6nKPDQzT8X',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ssJ3SyQwFkfGSMdk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/chat/messages/{adminId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'controller' => 'App\\Http\\Controllers\\ChatController@getMessages',
        'namespace' => NULL,
        'prefix' => 'mobile-api/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::ssJ3SyQwFkfGSMdk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::f7JLvkUQfmobm7Zr' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'mobile-api/chat/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'controller' => 'App\\Http\\Controllers\\ChatController@sendMessage',
        'namespace' => NULL,
        'prefix' => 'mobile-api/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::f7JLvkUQfmobm7Zr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gDy50pKsSq0xKV5u' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mobile-api/chat/unread-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'controller' => 'App\\Http\\Controllers\\ChatController@getUnreadCount',
        'namespace' => NULL,
        'prefix' => 'mobile-api/chat',
        'where' => 
        array (
        ),
        'as' => 'generated::gDy50pKsSq0xKV5u',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:56:"C:\\xampp\\htdocs\\MSWDO\\mswdo-analysis\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"000000000000053f0000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local.upload' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:56:"C:\\xampp\\htdocs\\MSWDO\\mswdo-analysis\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:325:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ReceiveFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"00000000000005f10000000000000000";}}',
        'as' => 'storage.local.upload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
