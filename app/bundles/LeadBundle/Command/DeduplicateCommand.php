<?php

namespace Mautic\LeadBundle\Command;

use Mautic\CoreBundle\Command\ModeratedCommand;
use Mautic\CoreBundle\Helper\PathsHelper;
use Mautic\LeadBundle\Deduplicate\ContactDeduper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeduplicateCommand extends ModeratedCommand
{
    /**
     * @var ContactDeduper
     */
    private $contactDeduper;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(ContactDeduper $contactDeduper, TranslatorInterface $translator, PathsHelper $pathsHelper)
    {
        parent::__construct($pathsHelper);

        $this->contactDeduper = $contactDeduper;
        $this->translator     = $translator;
    }

    public function configure()
    {
        parent::configure();

        $this->setName('mautic:contacts:deduplicate')
            ->setDescription('Merge contacts based on same unique identifiers')
            ->addOption(
                '--newer-into-older',
                null,
                InputOption::VALUE_NONE,
                'By default, this command will merge older contacts and activity into the newer. Use this flag to reverse that behavior.'
            )
            ->setHelp(
                <<<'EOT'
The <info>%command.name%</info> command will dedpulicate contacts based on unique identifier values. 

<info>php %command.full_name%</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newerIntoOlder = (bool) $input->getOption('newer-into-older');
        $count          = $this->contactDeduper->deduplicate($newerIntoOlder, $output);

        $output->writeln('');
        $output->writeln(
            $this->translator->trans(
                'mautic.lead.merge.count',
                [
                    '%count%' => $count,
                ]
            )
        );

        return 0;
    }
}
